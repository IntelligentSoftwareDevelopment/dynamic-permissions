<?php

declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\Policies;

use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Isoftd\DynamicPermissions\App\Contracts\PermissionsEnumInterface;
use Isoftd\DynamicPermissions\App\Services\PermissionNameBuilder;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class DynamicPolicy
{
    protected string $permissionsEnum;

    protected string $permissionNameSeparator;

    public function __construct(
    ) {
        /** @var string $permissionEnum */
        $permissionEnum = Config::get('dynamic-permissions.default-permission-enum');
        /** @var string $nameSeparator */
        $nameSeparator = Config::get('dynamic-permissions.permission-name-separator');
        $this->permissionsEnum = $permissionEnum;
        $this->permissionNameSeparator = $nameSeparator;
    }

    /**
     * @param  array<array-key, Model>  $arguments
     */
    public function __call(string $name, array $arguments): bool
    {
        $admin = $arguments[0] ?? null;

        if (is_null($admin)) {
            return false;
        }

        if (method_exists($this, $name)) {
            /** @phpstan-ignore-next-line */
            return $this->{$name}(...$arguments);
        }

        $permission = $this->permissionsEnum::tryFrom(Str::ucfirst(Str::camel($name)));

        if (is_null($permission)) {
            return false;
        }

        /** @phpstan-ignore-next-line */
        return $this->hasAccessTo($admin, $permission);
    }

    public function getPrefix(): ?string
    {
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        /** @phpstan-ignore-next-line */
        return $this->hasAccessTo($user, $this->permissionsEnum::VIEW_ANY);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Model $resource): bool
    {
        /** @phpstan-ignore-next-line */
        return $this->hasAccessTo($user, $this->permissionsEnum::VIEW) && $this->isBelongsTo($user, $resource);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Model $user): bool
    {
        /** @phpstan-ignore-next-line */
        return $this->hasAccessTo($user, $this->permissionsEnum::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Model $resource): bool
    {
        /** @phpstan-ignore-next-line */
        return $this->hasAccessTo($user, $this->permissionsEnum::UPDATE) && $this->isBelongsTo($user, $resource);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Model $resource): bool
    {
        /** @phpstan-ignore-next-line */
        return $this->hasAccessTo($user, $this->permissionsEnum::DELETE) && $this->isBelongsTo($user, $resource);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Model $user, Model $model): bool
    {
        /** @phpstan-ignore-next-line */
        return $this->hasAccessTo($user, $this->permissionsEnum::RESTORE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Model $user, Model $model): bool
    {
        /** @phpstan-ignore-next-line */
        return $this->hasAccessTo($user, $this->permissionsEnum::FORCE_DELETE);
    }

    protected function hasAccessTo(Model $user, PermissionsEnumInterface $permissionEnum): bool
    {
        $permission = $this->extractPermission($permissionEnum);

        try {
            if (method_exists($user, 'hasPermissionTo')) {
                // Check wildcard/ALL permission first to avoid exception when specific permission doesn't exist
                if ($this->userHasPermissionToAll($user)) {
                    return true;
                }

                /** @var bool $result */
                $result = $user->hasPermissionTo($permission);

                return $result;
            }

            return false;
        } catch (PermissionDoesNotExist) {
            // If permission doesn't exist, deny access for security
            return false;
        }
    }

    protected function extractPermission(PermissionsEnumInterface $permission): string
    {
        $className = class_basename(static::class);
        $class = Str::before($className, 'Policy');
        $prefix = $this->getPrefix();

        if (! is_null($prefix) && config('dynamic-permissions.policy-prefix')) {
            $class = PermissionNameBuilder::build($prefix, $class);
        }

        /** @phpstan-ignore-next-line */
        return PermissionNameBuilder::build($class, Str::studly($permission->value));
    }

    protected function userHasPermissionToAll(Model $user): bool
    {
        /** @phpstan-ignore-next-line */
        $superAdminPermission = $this->extractPermission($this->permissionsEnum::ALL);

        try {
            if (method_exists($user, 'hasPermissionTo')) {
                /** @phpstan-ignore-next-line */
                return $user->hasPermissionTo($superAdminPermission);
            }

            return false;
        } catch (PermissionDoesNotExist) {
            return true;
        }
    }

    protected function isBelongsTo(Model $owner, Model $resource): bool
    {
        return $this->userHasPermissionToAll($owner);
    }
}
