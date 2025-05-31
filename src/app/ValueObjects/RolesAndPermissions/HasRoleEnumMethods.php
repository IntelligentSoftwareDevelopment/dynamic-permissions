<?php
declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\ValueObjects\RolesAndPermissions;

use IntelligentSoftwareDevelopment\EnumMethods\Support\HasEnumMethods;
use Isoftd\DynamicPermissions\App\Contracts\RoleEnumInterface;
use Spatie\Permission\Models\Role;

/**
 * @method bool isSuperAdmin()
 * @method bool isAdmin()
 */
trait HasRoleEnumMethods
{
    use HasEnumMethods;

    public static function shouldBeSeeded(): array
    {
        return collect(static::cases())->filter(fn (RoleEnumInterface $role) => !$role->is(static::alreadySeeded()))->toArray();
    }

    public static function alreadySeeded(): array
    {
        return Role::all()->map(fn (Role $role) => static::try($role->name))->toArray();
    }

    public function getModel(): Role
    {
        return Role::where('name', $this->value)->first();
    }
}
