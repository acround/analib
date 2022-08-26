<?php

namespace analib\Core\Exceptions;

class FileException extends BaseException
{

    protected $code = -1300;
    protected int $httpCode = 400;
    protected $message = 'File error';

}
