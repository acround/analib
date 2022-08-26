<?php

namespace analib\Core\Exceptions;

class MissingElementException extends WrongArgumentException
{

    protected $code = -1710;
    protected int $httpCode = 400;
    protected $message = 'Missing element';

}
