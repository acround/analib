<?php

namespace analib\Core\Exceptions;

class FileNotFoundException extends NotFoundException
{

    protected $code = -1410;
    protected int $httpCode = 400;
    protected $message = 'File not found';

}
