<?php

namespace analib\Core\Exceptions;

class PropertyNotFoundException extends NotFoundException
{

    protected $code = -1430;
    protected int $httpCode = 400;
    protected $message = 'Property not found';

}
