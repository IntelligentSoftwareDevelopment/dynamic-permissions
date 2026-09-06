<?php

declare(strict_types=1);

use Isoftd\DynamicPermissions\App\ValueObjects\Policies;
use Isoftd\DynamicPermissions\App\ValueObjects\RolesAndPermissions\PermissionEnum;
use Isoftd\DynamicPermissions\App\ValueObjects\RolesAndPermissions\RoleEnum;

return [
    'default-role-enum' => RoleEnum::class,
    'default-permission-enum' => PermissionEnum::class,
    'default-policies-value-object' => Policies::class,
    'permission-container' => null,
    'policy-prefix' => false,
    'permission-name-separator' => '.',
    'value_objects_path' => app_path('ValueObjects'),
];
