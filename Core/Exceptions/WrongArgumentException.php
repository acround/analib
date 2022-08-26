<?php

namespace analib\Core\Exceptions;

/**
 * @author acround
 */
class WrongArgumentException extends BaseException
{

    protected $code = -1700;
    protected int $httpCode = 400;
    protected $message = 'Invalid argument';

}
