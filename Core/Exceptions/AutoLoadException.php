<?php

namespace analib\Core\Exceptions;

/**
 * @author acround
 */
class AutoLoadException extends BaseException
{

    protected $code     = -1100;
    protected int $httpCode = 400;
    protected $message  = 'Class not found';

}
