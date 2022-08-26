<?php

namespace analib\Core\Exceptions;

class FB2Exception extends XMLException
{

    protected $code = -1000;
    protected int $httpCode = 400;
    protected $message = 'FB2 error';

}
