<?php

namespace analib\Core\Exceptions;

class DBException extends BaseException
{

    protected $code = -1200;
    protected int $httpCode = 400;
    protected $message = 'Data base error';

}
