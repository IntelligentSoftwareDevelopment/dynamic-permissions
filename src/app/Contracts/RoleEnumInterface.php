<?php

declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\Contracts;

use BackedEnum;
use Spatie\Permission\Models\Role;

interface RoleEnumInterface extends BackedEnum
{
    /**
     * @return array<array-key, RoleEnumInterface>
     */
    public static function shouldBeSeeded(): array;

    /**
     * @return array<array-key, RoleEnumInterface>
     */
    public static function alreadySeeded(): array;

    /**
     * @param  array<array-key, RoleEnumInterface|null>  $cases
     */
    public function is(...$cases): bool;

    public function getModel(): ?Role;
}
