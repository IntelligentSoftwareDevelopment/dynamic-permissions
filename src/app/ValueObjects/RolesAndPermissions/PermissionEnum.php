<?php

declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\ValueObjects\RolesAndPermissions;

use Isoftd\DynamicPermissions\App\Contracts\PermissionsEnumInterface;

/**
 * @method isAll()
 * @method isView()
 * @method isViewAny()
 * @method isCreate()
 * @method isUpdate()
 * @method isDelete()
 * @method isRestore()
 * @method isForceDelete()
 */
enum PermissionEnum: string implements PermissionsEnumInterface
{
    use HasPermissionEnum;
    case ALL = '*';
    case VIEW = 'View';
    case VIEW_ANY = 'ViewAny';
    case CREATE = 'Create';
    case UPDATE = 'Update';
    case DELETE = 'Delete';
    case RESTORE = 'Restore';
    case FORCE_DELETE = 'ForceDelete';
}
