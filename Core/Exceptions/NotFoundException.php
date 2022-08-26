<?php

namespace analib\Core\Exceptions;

class NotFoundException extends BaseException
{

    protected $code = -1400;
    protected int $httpCode = 400;
    protected $message = 'Not found';

}
