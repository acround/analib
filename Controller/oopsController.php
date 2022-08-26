<?php

namespace analib\Controller;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

use analib\Core\Application\Application;
use analib\Core\Exceptions\BaseException;
use analib\Util\Session;

class oopsController extends BaseController
{

    protected $leftMenu;
    protected $controllerTitle = 'Oops!';
    protected $full = false;

    /**
     * @var BaseException
     */
    protected $error;

    public function setError(BaseException $error)
    {
        $this->error = $error;
        return $this;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     *
     * @param BaseException $error
     * @return oopsController
     */
    public static function create(BaseException $error)
    {
        $errorController = new self();
        Application::me()->
        setControllerName(BaseController::ERROR_CONTROLLER)->
        setController($errorController);
        $errorController->setError($error);
        if (Application::me()->getDebug() & (E_ERROR | E_NOTICE)) {
            Application::me()->
            setAction('debug')->
            setActionName('debug') . ANALIB_SUF_ACTION;
            $actionName = BaseController::DEBUG_ACTION . ANALIB_SUF_ACTION;
        } else {
            Application::me()->
            setAction('index')->
            setActionName('index') . ANALIB_SUF_ACTION;
            $actionName = BaseController::DEFAULT_ACTION . ANALIB_SUF_ACTION;
        }
        $errorController->$actionName();
        return $errorController;
    }

    public function full()
    {
        $this->full = true;
        return $this;
    }

    public function isFull()
    {
        return $this->full;
    }

    public function indexAction()
    {
        if ($this->error) {
            $code = $this->error->getCode();
            $message = $this->error->getMessage();
            $type = get_class($this->error);
        } else {
            $code = Session::get('error_id');
            $message = Session::get('error');
            $type = Session::get('error_type');
            Session::drop('error_id');
            Session::drop('error');
            Session::drop('error_type');
        }

        switch ($type) {
            case 'ForbiddenException':
                $code = 403;
                break;
            case 'PageNotFoundException':
                $code = 404;
                break;
        }

        Application::me()->
        setOutParam('ERROR', $message)->
        setOutParam('ERROR_CODE', $code)->
        setOutParam('ERROR_TYPE', $type);
        return $this;
    }

    public function debugAction()
    {
        Application::me()->setOutParam('EXCEPTION', $this->error);
        return $this;
    }

}
