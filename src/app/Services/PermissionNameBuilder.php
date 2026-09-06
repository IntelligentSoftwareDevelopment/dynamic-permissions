<?php

declare(strict_types=1);

namespace Isoftd\DynamicPermissions\App\Services;

class PermissionNameBuilder
{
    /**
     * @param  array<string>  $args
     */
    public static function build(...$args): string
    {
        /** @var string $separator */
        $separator = config('dynamic-permissions.permission-name-separator');

        /** @var array<string> $args */
        return implode($separator, $args);
    }
}
