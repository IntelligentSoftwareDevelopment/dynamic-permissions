<?php

declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\Contracts;

interface PermissionContainerInterface
{
    /**
     * @return array<string, array<string, array<string, array<array-key, RoleEnumInterface>>>>
     */
    public function permissions(): array;
}
