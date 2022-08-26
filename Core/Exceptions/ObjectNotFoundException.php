<?php

namespace analib\Core\Exceptions;

class ObjectNotFoundException extends NotFoundException
{

    protected $code     = -1420;
    protected int $httpCode = 400;
    protected $message  = 'Object not found';

}
