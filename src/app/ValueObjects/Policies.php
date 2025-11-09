<?php

declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\ValueObjects;

use Isoftd\DynamicPermissions\App\Contracts\PolicyInterface;

final class Policies implements PolicyInterface
{
    private const array POLICIES = [
    ];

    public static function getPolicies(): array
    {
        return self::POLICIES;
    }
}
