<?php
declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\ValueObjects\RolesAndPermissions;

use Isoftd\DynamicPermissions\App\Contracts\RoleEnumInterface;
enum RoleEnum: string implements RoleEnumInterface
{
    use HasRoleEnumMethods;
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
}
