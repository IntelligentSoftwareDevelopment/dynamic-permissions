<?php

declare(strict_types=1);

use Isoftd\DynamicPermissions\App\ValueObjects\Policies;
use Isoftd\DynamicPermissions\App\ValueObjects\RolesAndPermissions\PermissionEnum;
use Isoftd\DynamicPermissions\App\ValueObjects\RolesAndPermissions\RoleEnum;

return [
    'default-role-enum' => RoleEnum::class,
    'default-permission-enum' => PermissionEnum::class,
    'default-policies-value-object' => Policies::class,
    'models_with_base_policy' => [

    ],
];
