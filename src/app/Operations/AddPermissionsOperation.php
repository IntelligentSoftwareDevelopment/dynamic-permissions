<?php
declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\Operations;

use BackedEnum;
use DragonCode\LaravelDeployOperations\Operation;
use Illuminate\Support\Collection;
use Isoftd\DynamicPermissions\App\Contracts\RoleEnumInterface;
use Spatie\Permission\Models\Role;

class AddPermissionsOperation extends Operation
{
    /** @var Collection<array-key, Role> */
    protected Collection $roles;

    public const string OPERATION = '';

    public function __construct()
    {
        $roleEnum = config('dynamic-permissions.default-role-enum');
        $this->roles = collect();
        if ($roleEnum instanceof RoleEnumInterface && $roleEnum instanceof BackedEnum) {
            foreach ($roleEnum::cases() as $case) {
                $this->roles->push($case->getModel());
            }
        }
    }

    public function up(): void
    {
        PermissionsService::addPermissionsToRoles($this->roles, static::OPERATION);
    }

    public function down(): void
    {
        PermissionsService::removePermissionsFromRoles($this->roles, static::OPERATION);
    }
}
