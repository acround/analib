<?php

namespace analib\Core\Exceptions;

class FTPException extends BaseException
{

    protected $code = -1500;
    protected int $httpCode = 400;
    protected $message = 'FTP error';

}
