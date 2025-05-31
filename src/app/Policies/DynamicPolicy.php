<?php
declare (strict_types = 1);

namespace Isoftd\DynamicPermissions\App\Policies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Isoftd\DynamicPermissions\App\ValueObjects\RolesAndPermissions\PermissionEnum;

class DynamicPolicy
{
    protected string $permissionsEnum;
    public function __construct(
    ){
        $this->permissionsEnum = config('dynamic-permissions.default-permissions-value-object');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        return $this->hasAccessTo($user, $this->permissionsEnum::VIEW_ANY);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Model $resource): bool
    {
        return $this->hasAccessTo($user, PermissionEnum::VIEW) && $this->isBelongsTo($user, $resource);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Model $user): bool
    {
        return $this->hasAccessTo($user, PermissionEnum::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Model $resource): bool
    {
        return $this->hasAccessTo($user, PermissionEnum::UPDATE) && $this->isBelongsTo($user, $resource);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Model $resource): bool
    {
        return $this->hasAccessTo($user, PermissionEnum::DELETE) && $this->isBelongsTo($user, $resource);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Model $user, Model $model): bool
    {
        return $this->hasAccessTo($user, PermissionEnum::RESTORE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Model $user, Model $model): bool
    {
        return $this->hasAccessTo($user, PermissionEnum::FORCE_DELETE);
    }

    protected function hasAccessTo(Model $user, PermissionEnum $permissionEnum): bool
    {
        $permission = $this->extractPermission($permissionEnum);

        return $user->hasPermissionTo($permission) || $this->userHasPermissionToAll($user);
    }

    protected function extractPermission(PermissionEnum $permission): string
    {
        $class = Str::before(class_basename(static::class), 'Policy');

        return $class . '.' . Str::studly($permission->value);
    }

    protected function userHasPermissionToAll(Model $user): bool
    {
        $superAdminPermission = $this->extractPermission(PermissionEnum::ALL);

        return $user->hasPermissionTo($superAdminPermission);
    }

    protected function isBelongsTo(Model $author, Model $resource): bool
    {
        return $this->userHasPermissionToAll($author);
    }
}
