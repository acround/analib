<?php

namespace analib\Controller;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

use analib\Core\Application\Application;
use analib\Core\Exceptions\ForbiddenException;

class AjaxResultModule extends BaseController
{

    protected $leftMenu = false;

    public function __construct()
    {
        if (!Application::isAjax()) {
            header('HTTP/1.1 403 Forbidden');
            die('<!-- To have an inquiring nature is not a crime ! :) -->');
        }

        try {
            parent::__construct();
        } catch (ForbiddenException $e) {

        }
    }

}
