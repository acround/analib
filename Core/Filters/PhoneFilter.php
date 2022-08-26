<?php

namespace analib\Core\Filters;

/**
 *
 * @author acround
 */
class PhoneFilter extends StringFilter
{

    public static function test($value): bool
    {
        self::$filterPattern = self::FILTER_PHONE;
        return parent::test($value);
    }

}
