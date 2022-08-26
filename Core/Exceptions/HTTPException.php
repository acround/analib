<?php

namespace analib\Core\Exceptions;

class HTTPException extends BaseException
{

    protected $code = -1800;
    protected int $httpCode = 400;
    protected $message = 'HTTP error';

}
