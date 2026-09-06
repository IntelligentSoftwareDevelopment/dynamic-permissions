<?php

declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\Services;

use Exception;
use Illuminate\Support\Collection;
use Isoftd\DynamicPermissions\App\ValueObjects\PermissionContainer;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsService
{
    /**
     * @param  Collection<array-key, Role>  $roles
     *
     * @throws Exception
     */
    public static function addPermissionsToRoles(Collection $roles, string $which, ?string $prefix = null): void
    {
        self::execute($roles, $which, PermissionsFunction::ASSIGN_ROLE, $prefix);
    }

    /**
     * @param  Collection<array-key, Role>  $roles
     *
     * @throws Exception
     */
    public static function removePermissionsFromRoles(Collection $roles, string $which, ?string $prefix = null): void
    {
        self::execute($roles, $which, PermissionsFunction::REMOVE_ROLE, $prefix);
    }

    /**
     * @param  Collection<array-key, Role>  $roles
     *
     * @throws Exception
     */
    private static function execute(Collection $roles, string $which, PermissionsFunction $function, ?string $prefix): void
    {
        $listedPermissions = PermissionContainer::get($which);

        foreach ($listedPermissions as $class => $item) {
            foreach ($item as $permission => $actualRoles) {
                $nameOfPermission = PermissionNameBuilder::build($class, $permission);
                if ($prefix && config('dynamic-permissions.policy-prefix')) {
                    $nameOfPermission = PermissionNameBuilder::build($prefix, $nameOfPermission);
                }
                $listedPermission = match (true) {
                    $function->isAssignRole() => Permission::query()->create(['name' => $nameOfPermission]),
                    $function->isRemoveRole() => Permission::query()->where('name', $nameOfPermission)->first(),
                    default => throw new Exception('The method is not implemented!'),
                };

                /** @var class-string $roleEnum */
                $roleEnum = config('dynamic-permissions.default-role-enum');

                $roles->each(static function (Role $role) use ($function, $listedPermission, $actualRoles, $roleEnum): void {
                    if (in_array($roleEnum::tryFrom($role->name), $actualRoles, true)) {
                        $listedPermission?->{$function->value}($role);
                    }
                });

                if ($function->isRemoveRole()) {
                    $listedPermission?->delete();
                }
            }
        }
    }
}
