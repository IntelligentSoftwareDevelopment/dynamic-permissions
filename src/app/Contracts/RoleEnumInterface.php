<?php
declare (strict_types = 1);

namespace Isoftd\DynamicPermissions\App\Contracts;

use Spatie\Permission\Models\Role;

interface RoleEnumInterface
{
    public function is(...$cases): bool;
    public static function shouldBeSeeded(): array;
    public static function alreadySeeded(): array;
    public function getModel(): Role;
}
