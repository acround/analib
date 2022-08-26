<?php

namespace analib\Core\Exceptions;

class SystemException extends BaseException
{

    protected $code = -1600;
    protected int $httpCode = 400;
    protected $message = 'System error';

}
