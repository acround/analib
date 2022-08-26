<?php

namespace analib\Core\Exceptions;

class FileUploadException extends FileException
{

    protected $code = -1320;
    protected int $httpCode = 400;
    protected $message = 'File upload error';

}
