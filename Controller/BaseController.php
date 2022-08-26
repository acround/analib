<?php

namespace analib\Controller;

use analib\Core\Application\Application;
use analib\Util\Template;

/**
 *
 * @author acround
 */
abstract class BaseController
{

    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = 'index';
    const ERROR_CONTROLLER = 'oops';
    const DEBUG_ACTION = 'debug';
    const DEFAULT_ROW_AMOUNT = 20;

    protected $name = '';
    protected $template = '';
    protected $controllerTitle = null;
    protected $actionTitles = array();
    protected $leftMenu = true;
    protected $errors = array();

    /**
     *
     * @var \analib\Core\Form\Form
     */
    protected $form = null;

    public function __construct()
    {
        $name = get_class($this) . "\n";
        $this->name = str_replace(ANALIB_SUF_CONTROLLER . "\n", '', $name);
        Application::me()->
        setOutParam('controller', $this->name)->
        setOutParam('controllerTitle', $this->controllerTitle);
        $this->form = \analib\Core\Form\Form::create();
    }

    /**
     *
     * @return string
     */
    public static function getIP()
    {
        return filter_input(INPUT_SERVER, 'REMOTE_ADDR');
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        if (!$this->name) {
            $name = get_class($this) . "\n";
            $this->name = str_replace(ANALIB_SUF_CONTROLLER . "\n", '', $name);
        }
        return $this->name;
    }

    public function getControllerTitle()
    {
        return $this->controllerTitle;
    }

    /**
     * @param string $title
     * @return BaseController
     */
    public function setControllerTitle($title)
    {
        $this->controllerTitle = $title;
        return $this;
    }

    public function getActionTitle()
    {
        $action = Application::me()->getAction();
        if (isset($this->actionTitles[$action])) {
            return $this->actionTitles[$action];
        } else {
            return $this->controllerTitle;
        }
    }

    /**
     *
     * @param string $title
     * @param string $action
     * @return BaseController
     */
    public function setActionTitle($title, $action = null)
    {
        $action = $action ? $action : Application::me()->getAction();
        $this->actionTitles[$action] = $title;
        return $this;
    }

    /**
     *
     * @param BaseException $e
     * @return BaseController
     */
    public function addError(BaseException $e)
    {
        $this->errors[] = $e;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @return BaseController
     */
    public function clearErrors()
    {
        $this->errors = array();
        return $this;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     *
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     *
     * @return BaseController
     */
    public function indexAction()
    {
        return $this;
    }

    /**
     *
     * @return string
     */
    public function dump()
    {
        extract(Application::me()->getOutParams(), EXTR_OVERWRITE);
        $view = Application::me()->getTemplate();
        ob_start();
        if (file_exists($view)) {
            include $view;
        } else {
            include Template::includeActionTemplate();
        }
//		$out = ob_get_clean();
        $out = ob_get_contents();
        ob_end_clean();
//		$out = ob_end_clean();
        return $out;
    }

}
