<?php

namespace analib\Core\Exceptions;

class FormException extends BaseException
{

    protected $code = -1900;
    protected int $httpCode = 400;
    protected $message = 'Form error';

}
