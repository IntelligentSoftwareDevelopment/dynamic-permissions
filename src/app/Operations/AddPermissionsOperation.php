<?php

declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\Operations;

use DragonCode\LaravelDeployOperations\Operation;
use Exception;
use Illuminate\Support\Collection;
use Isoftd\DynamicPermissions\App\Contracts\RoleEnumInterface;
use Isoftd\DynamicPermissions\App\Services\PermissionsService;
use Spatie\Permission\Models\Role;

class AddPermissionsOperation extends Operation
{
    public const string OPERATION = '';

    public const string|null PREFIX = null;

    /** @var Collection<array-key, Role> */
    private Collection $roles;

    private ?string $prefix = null;

    public function __construct()
    {
        $this->loadRoles();
        $this->loadPrefix();
    }

    /**
     * @throws Exception
     */
    public function up(): void
    {
        PermissionsService::addPermissionsToRoles(
            roles: $this->roles,
            which: static::OPERATION,
            prefix: $this->prefix,
        );
    }

    /**
     * @throws Exception
     */
    public function down(): void
    {
        PermissionsService::removePermissionsFromRoles(
            roles: $this->roles,
            which: static::OPERATION,
            prefix: $this->prefix,
        );
    }

    protected function loadRoles(): void
    {
        /** @var class-string<RoleEnumInterface> $roleEnum */
        $roleEnum = config('dynamic-permissions.default-role-enum');
        /** @var Collection<array-key, Role> $roles */
        $roles = collect();
        foreach ($roleEnum::cases() as $case) {
            /** @var Role|null $role */
            $role = $case->getModel();

            if (is_null($role)) {
                continue;
            }
            $roles->push($role);
        }

        $this->roles = $roles;
    }

    protected function loadPrefix(): void
    {
        if (config('dynamic-permissions.policy-prefix')) {
            $this->prefix = static::PREFIX;
        }
    }
}
