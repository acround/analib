<?php

namespace analib\Core\Exceptions;

class PDFException extends FileException
{

    protected $code = -1310;
    protected int $httpCode = 400;
    protected $message = 'PDF error';

}
