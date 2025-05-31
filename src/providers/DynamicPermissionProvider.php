<?php
declare(strict_types=1);

namespace Isoftd\DynamicPermissions\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Isoftd\DynamicPermissions\App\Contracts\PolicyInterface;

class DynamicPermissionProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishAssets();
        $this->loadPolicies();
    }

    private function publishAssets(): void
    {
        $this->publishes([
            __DIR__ . '/../config/dynamic-permissions.php' => config_path('dynamic-permissions.php'),
        ], 'dynamic-permissions-config');

        $this->publishes([
            __DIR__ . '/../app/ValueObjects' => app_path('Domains/DynamicPermissions/ValueObjects'),
        ], 'dynamic-permissions-value-object');
    }

    private function loadPolicies(): void
    {
        $policiesValueObject = config('dynamic-permissions.default-policies-value-object');

        if (!$policiesValueObject instanceof PolicyInterface) {
            return;
        }

        foreach ($policiesValueObject::getPolicies() as $class => $policy) {
            Gate::policy($class, $policy);
        }
    }
}
