<?php
declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\Services;

use Exception;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsService
{
    /**
     * @param Collection<array-key, Role> $roles
     * @param string $which
     * @throws Exception
     * @return void
     */
    public static function addPermissionsToRoles(Collection $roles, string $which): void
    {
        self::execute($roles, $which, PermissionsFunction::ASSIGN_ROLE);
    }

    /**
     * @param Collection<array-key, Role> $roles
     * @param string $which
     * @throws Exception
     * @return void
     */
    public static function removePermissionsFromRoles(Collection $roles, string $which): void
    {
        self::execute($roles, $which, PermissionsFunction::REMOVE_ROLE);
    }

    /**
     * @param Collection<array-key, Role> $roles
     * @param string $which
     * @param PermissionsFunction $function
     * @throws Exception
     * @return void
     */
    private static function execute(Collection $roles, string $which, PermissionsFunction $function): void
    {
        $listedPermissions = self::PERMISSIONS[$which];

        foreach ($listedPermissions as $class => $item) {
            foreach ($item as $permission => $actualRoles) {
                $nameOfPermission = $class . '.' . $permission;
                $listedPermission = match (true) {
                    $function->isAssignRole() => Permission::create(['name' => $nameOfPermission]),
                    $function->isRemoveRole() => Permission::where('name', $nameOfPermission)->first(),
                    default => throw new Exception('The method is not implemented!'),
                };

                $roles->each(function (Role $role) use ($function, $listedPermission, $actualRoles) {
                    if (in_array(RoleEnum::tryFrom($role->name), $actualRoles)) {
                        $listedPermission->{$function->value}($role);
                    }
                });

                if ($function->isRemoveRole()) {
                    $listedPermission->delete();
                }
            }
        }
    }

    private const array PERMISSIONS = [
        'first_operation' => [
            'User' => self::ONLY_ADMINS,
            'ChatGPTService' => self::ONLY_ADMINS,
            'ChatGptTask' => self::ADMINS_AND_OWNERS,
            'Conversation' => self::ONLY_ADMINS,
        ],
        'feature_flags_operation' => [
            'FeatureFlag' => self::ONLY_ADMINS,
        ],
        'amazon_domains_operation' => [
            'AmazonDomain' => self::ONLY_ADMINS,
        ],
        'amazon_communicators' => [
            'AmazonInformation' => self::ONLY_ADMINS,
            'AmazonJob' => self::ONLY_ADMINS,
            'AmazonProduct' => self::ADMINS_AND_OWNERS,
        ],
        'payments_packages' => [
            'Package' => self::ONLY_ADMINS,
        ],
        'payments_wallet_and_transactions' => [
            'Transaction' => self::ADMINS_AND_OWNERS,
            'Wallet' => self::ADMINS_AND_OWNERS,
        ],
        'add_for_settings_model' => [
            'Setting' => self::ADMINS_AND_OWNERS,
        ],
        'add_new_for_settings_model' => [
            'Setting' => self::ONLY_ADMINS,
        ],
        'add_for_exports' => [
            'AmazonProductExport' => self::ADMINS_AND_OWNERS,
        ],
    ];

//    private const array ONLY_ADMINS = [
//        PermissionEnum::ALL->value => [
//            RoleEnum::SUPER_ADMIN,
//        ],
//        PermissionEnum::VIEW->value => [
//            RoleEnum::ADMIN,
//        ],
//        PermissionEnum::VIEW_ANY->value => [
//            RoleEnum::ADMIN,
//        ],
//        PermissionEnum::CREATE->value => [
//            RoleEnum::ADMIN,
//        ],
//        PermissionEnum::UPDATE->value => [
//            RoleEnum::ADMIN,
//        ],
//        PermissionEnum::DELETE->value => [
//            RoleEnum::ADMIN,
//        ],
//        PermissionEnum::RESTORE->value => [
//            RoleEnum::ADMIN,
//        ],
//        PermissionEnum::FORCE_DELETE->value => [
//            RoleEnum::ADMIN,
//        ],
//    ];
//
//    private const array ADMINS_AND_OWNERS = [
//        PermissionEnum::ALL->value => [
//            RoleEnum::SUPER_ADMIN,
//        ],
//        PermissionEnum::VIEW->value => [
//            RoleEnum::ADMIN,
//            RoleEnum::CUSTOMER,
//        ],
//        PermissionEnum::VIEW_ANY->value => [
//            RoleEnum::ADMIN,
//            RoleEnum::CUSTOMER,
//        ],
//        PermissionEnum::CREATE->value => [
//            RoleEnum::ADMIN,
//            RoleEnum::CUSTOMER,
//        ],
//        PermissionEnum::UPDATE->value => [
//            RoleEnum::ADMIN,
//            RoleEnum::CUSTOMER,
//        ],
//        PermissionEnum::DELETE->value => [
//            RoleEnum::ADMIN,
//        ],
//        PermissionEnum::RESTORE->value => [
//            RoleEnum::ADMIN,
//        ],
//        PermissionEnum::FORCE_DELETE->value => [
//            RoleEnum::ADMIN,
//        ],
//    ];
}
