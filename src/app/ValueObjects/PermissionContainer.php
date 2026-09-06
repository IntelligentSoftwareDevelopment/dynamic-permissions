<?php

declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\ValueObjects;

use Isoftd\DynamicPermissions\App\Contracts\PermissionContainerInterface;
use Isoftd\DynamicPermissions\App\Contracts\RoleEnumInterface;

readonly class PermissionContainer
{
    /**
     * @return array<string, array<string, array<array-key, RoleEnumInterface>>>|array<string, array<string, array<string, array<array-key, RoleEnumInterface>>>>
     */
    public static function get(?string $key): array
    {
        $container = new (config('dynamic-permissions.permission-container'));

        return match (true) {
            ! $container instanceof PermissionContainerInterface => [],
            default => self::getPermissions($container, $key),
        };
    }

    /**
     * @return array<string, array<string, array<array-key, RoleEnumInterface>>>|array<string, array<string, array<string, array<array-key, RoleEnumInterface>>>>
     */
    private static function getPermissions(PermissionContainerInterface $container, ?string $key): array
    {
        $permissions = $container->permissions();

        if (is_null($key)) {
            return $permissions;
        }

        return $permissions[$key] ?? [];
    }
}
