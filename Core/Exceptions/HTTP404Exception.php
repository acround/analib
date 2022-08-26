<?php

namespace analib\Core\Exceptions;

class HTTP404Exception extends HTTPException
{

    protected $code = -1810;
    protected int $httpCode = 404;
    protected $message = 'Page not found';

}
