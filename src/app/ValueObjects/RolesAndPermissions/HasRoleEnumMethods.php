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

    /**
     * @return array<array-key, RoleEnumInterface>
     */
    public static function shouldBeSeeded(): array
    {
        /** @phpstan-ignore-next-line */
        return collect(static::cases())->filter(fn (RoleEnumInterface $role): bool => ! $role->is(static::alreadySeeded()))->toArray();
    }

    /**
     * @return array<array-key, RoleEnumInterface>
     */
    public static function alreadySeeded(): array
    {
        /** @phpstan-ignore-next-line */
        return Role::query()->get()->map(fn (Role $role): ?RoleEnumInterface => static::try($role->name))->toArray();
    }

    public function getModel(): ?Role
    {
        return Role::query()->where('name', $this->value)->first();
    }
}
