<?php

namespace analib\Core\Filters;

/**
 *
 * @author acround
 */
class UrlFilter extends StringFilter
{

    public static function test($value): bool
    {
        self::$filterPattern = self::FILTER_URL;
        return parent::test($value);
    }

}
