<?php

namespace analib\Core\Exceptions;

class ClassNotFoundException extends NotFoundException
{

    protected $code = -1000;
    protected int $httpCode = 400;
    protected $message = 'Class not found';

}
