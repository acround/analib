<?php

namespace analib\Core\Filters;

/**
 *
 * @author acround
 */
class EmailFilter extends StringFilter
{

    public static function test($value): bool
    {
        self::$filterPattern = self::FILTER_EMAIL;
        return parent::test($value);
    }

}
