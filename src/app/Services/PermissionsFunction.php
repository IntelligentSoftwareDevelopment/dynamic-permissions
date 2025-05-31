<?php
declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\Services;

use IntelligentSoftwareDevelopment\EnumMethods\Support\HasEnumMethods;

/**
 * @method bool isAssignRole()
 * @method bool isRemoveRole()
 */
enum PermissionsFunction: string
{
    use HasEnumMethods;
    case ASSIGN_ROLE = 'assignRole';
    case REMOVE_ROLE = 'removeRole';
}
