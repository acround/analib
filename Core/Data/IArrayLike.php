<?php

namespace analib\Core\Data;

use ArrayAccess;

/**
 *
 * @author acround
 */
interface IArrayLike extends ArrayAccess
{

    public function keys();

    public function values();
}
