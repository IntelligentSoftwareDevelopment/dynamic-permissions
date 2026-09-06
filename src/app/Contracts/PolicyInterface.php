<?php

declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\Contracts;

interface PolicyInterface
{
    /**
     * @return array<class-string, class-string>
     */
    public static function getPolicies(): array;
}
