<?php

namespace analib\Core\Exceptions;

class NoLoginException extends BaseException
{

    protected $code = -2000;
    protected int $httpCode = 400;
    protected $message = 'Authorization is required';

}
