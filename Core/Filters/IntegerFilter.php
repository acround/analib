<?php

namespace analib\Core\Filters;

/**
 *
 * @author acround
 */
class IntegerFilter extends BaseFilter
{

    public static function test($value): bool
    {
        if (is_int($value)) {
            return true;
        }

        if (preg_match('/^[\d]+$/', (string)$value)) {
            return true;
        } else {
            return false;
        }
    }

}
