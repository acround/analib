<?php

namespace analib\Core\Filters;

use analib\Util\StaticFactory;

/**
 *
 * @author acround
 */
class BaseFilter extends StaticFactory
{

    public static function test($value): bool
    {
        return true;
    }

}
