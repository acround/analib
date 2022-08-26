<?php

namespace analib\Core\Exceptions;

class HTTP403Exception extends HTTPException
{

    protected $code = -1820;
    protected int $httpCode = 403;
    protected $message = 'Forbidden';

}
