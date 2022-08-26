<?php

namespace analib\Core\Application;

use analib\Controller\BaseController;
use analib\Util\DebugUtils;

/**
 * WEB-Приложение
 *
 * @author acround
 */
class Application
{

    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = 'index';
    const NOT_FOUND_CONTROLLER = 'index';
    const VAR_USER = 'user';
    const VAR_CONTROLLER = 'controller';
    const VAR_CONTROLLER_NAME = 'controllerName';
    const VAR_ACTION = 'action';
    const VAR_ACTION_NAME = 'actionName';
    const VAR_DEBUG = 'DEBUG';
    const ANALIB_EXT_CSS = '.css';
    const ANALIB_EXT_JS = '.js';

    /**
     * @var Application
     */
    static protected $instance;

    /**
     * @var IRouterInterface
     */
    static protected $router;

    /*
     * Параметры запроса
     */
    protected $getParams = array();
    protected $postParams = array();
    protected $addressRow = [];
    /**
     * @var string
     */
    protected $controllerName;
    /**
     * @var string
     */
    protected $actionName;
    /**
     * @var string
     */
    protected $action;
    /*
     * Выходные переменные
     */
    protected $outParams = array();
    /*
     * Настройки
     */
    protected $settings = array();

    /**
     * @var BaseController
     */
    protected $controller;
    protected $template;

    final protected function __construct()
    {

    }

    /**
     *
     * @return Application
     */
    public static function me()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     *
     * @return Application
     */
    public function init()
    {
        $this->
        initRouter()->
        getRouter()->
        route();
        return $this;
    }

    /**
     *
     * @return Application
     */
    protected function initRouter()
    {
        if (!self::$router) {
            self::$router = DefaultRouter::create();
        }
        return $this;
    }

    /**
     *
     * @return Application
     */
    public function run()
    {
        $controller = $this->getController();
        $actionName = $this->getActionName();
        if ($controller->$actionName()) {
            echo $controller->dump();
        }
        return $this;
    }

    /**
     *
     * @param IRouterInterface $router
     * @return Application
     */
    public function setRouter(IRouterInterface $router)
    {
        self::$router = $router;
        return $this;
    }

    /**
     *
     * @return IRouterInterface
     */
    protected function getRouter()
    {
        return self::$router;
    }

    /**
     *
     * @param BaseController $controller
     * @return Application
     */
    public function setController(BaseController $controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     *
     * @return BaseController
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     *
     * @param string $controllerName
     * @return Application
     */
    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     *
     * @param string $action
     * @return Application
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     *
     * @param string $actionName
     * @return Application
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     *
     * @param array $addressRow
     * @return Application
     */
    public function setAddressRow(array $addressRow = null)
    {
        $this->addressRow = $addressRow;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getAddressRow()
    {
        return $this->addressRow;
    }

    /**
     *
     * @return string
     */
    public function getAddressElement($number = 0)
    {
        if (isset($this->addressRow[$number])) {
            return $this->addressRow[$number];
        }

        return null;
    }

    /**
     *
     * @return string
     */
    public function getTemplate()
    {
        $view = $this->get('views_dir') .
            DIRECTORY_SEPARATOR .
            $this->getControllerName() .
            $this->get('view_ext');
        if (!file_exists($view)) {
            $view = $this->get('views_dir_lib') .
                DIRECTORY_SEPARATOR .
                $this->getControllerName() .
                $this->get('view_ext');
        }
        return $view;
    }

    /**
     *
     * @param string $template
     * @return Application
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getGetParams()
    {
        return $this->getParams;
    }

    /**
     *
     * @param array|null $getParams
     * @return Application
     */
    public function setGetParams(array $getParams = null)
    {
        $this->getParams = $getParams;
        return $this;
    }

    /**
     *
     * @param string $param
     * @return boolean
     */
    public function hasGetParam($param)
    {
        return isset($this->getParams[$param]);
    }

    /**
     *
     * @param string $param
     * @param string $defaultValue
     * @param int $index
     * @return mixed
     */
    public function getGetParam($param, $defaultValue = null, $index = null)
    {
        if (isset($this->getParams[$param])) {
            if ($index === null) {
                return $this->getParams[$param];
            }

            if (isset($this->getParams[$param][$index])) {
                return $this->getParams[$param][$index];
            }

            return null;
        }

        return $defaultValue;
    }

    /**
     *
     * @return array
     */
    public function getPostParams()
    {
        return $this->postParams;
    }

    /**
     *
     * @param array $postParams
     * @return Application
     */
    public function setPostParams(array $postParams = null)
    {
        $this->postParams = $postParams;
        return $this;
    }

    /**
     *
     * @param string $param
     * @return boolean
     */
    public function hasPostParam($param)
    {
        return isset($this->postParams[$param]);
    }

    /**
     *
     * @param string $param
     * @param string $defaultValue
     * @param int $index
     * @return mixed
     */
    public function getPostParam($param, $defaultValue = null, $index = null)
    {
        if (isset($this->postParams[$param])) {
            if ($index === null) {
                return $this->postParams[$param];
            }

            if (isset($this->postParams[$param][$index])) {
                return $this->postParams[$param][$index];
            }

            return null;
        }

        return $defaultValue;
    }

    /**
     *
     * @param string $param
     * @return string
     */
    public function getParam($param)
    {
        $tmp = (isset($this->getParams[$param]) ? $this->getParams[$param] : null);
        return isset($this->postParams[$param]) ? $this->postParams[$param] : $tmp;
    }

    /**
     *
     * @param string $param
     * @return boolean
     */
    public function hasParam($param)
    {
        return $this->hasGetParam($param) || $this->hasPostParam($param);
    }

    /**
     *
     * @param string $param
     * @param mixed $value
     * @return Application
     */
    public function setOutParam($param, $value = null)
    {
        $this->outParams[$param] = $value;
        return $this;
    }

    /**
     *
     * @param string $param
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getOutParam($param, $defaultValue = null)
    {
        return isset($this->outParams[$param]) ? $this->outParams[$param] : $defaultValue;
    }

    /**
     *
     * @return array
     */
    public function getOutParams()
    {
        return $this->outParams;
    }

    /**
     *
     * @param string $param
     * @return Application
     */
    public function dropOutParam($param)
    {
        unset($this->outParams[$param]);
        return $this;
    }

    /**
     *
     * @return Application
     */
    public function dumpOutParams()
    {
        if (self::me()->getDebug()) {
            DebugUtils::dumpFormat($this->outParams);
        }
        return $this;
    }

    /**
     *
     * @return Application
     */
    public function clearOutParams()
    {
        $this->outParams = array();
        return $this;
    }

    /**
     *
     * @param string $name
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->settings[$name]);
    }

    /**
     *
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get($name, $defaultValue = null)
    {
        if (isset($this->settings[$name])) {
            return $this->settings[$name];
        }

        return $defaultValue;
    }

    /**
     *
     * @return Application
     */
    public function set($name, $value = null)
    {
        $this->settings[$name] = $value;
        return $this;
    }

    /**
     *
     * @return Application
     */
    public function drop($name)
    {
        if (isset($this->settings[$name])) {
            unset($this->settings[$name]);
            return $this;
        }

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getDebug()
    {
        return $this->get(self::VAR_DEBUG, false);
    }

    /**
     *
     * @return array
     */
    public function getAll()
    {
        return $this->settings;
    }

    /**
     *
     * @return boolean
     */
    public static function isAjax()
    {
        return filter_has_var(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') && (filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest');
    }

    public static function isConsole()
    {
        return PHP_SAPI === 'cli' || (!filter_has_var(INPUT_SERVER, 'DOCUMENT_ROOT') && !filter_has_var(INPUT_SERVER, 'REQUEST_URI'));
    }

    /**
     *
     * @param string $controller
     * @param string $action
     * @param array $params
     * @param string $anchor
     * @return string
     */
    public static function makeUrl($controller = null, $action = null, array $params = null, $anchor = null)
    {
        return
            self::me()->
            initRouter()->
            getRouter()->
            makeUrl($controller, $action, $params, $anchor);
    }

    /**
     *
     * @param string $controller
     * @param string $action
     * @param array $params
     * @param string $anchor
     * @return string
     */
    public static function makeAbsUrl($controller = null, $action = null, array $params = null, $anchor = null)
    {
        return self::me()->get('protocol') .
            '://' .
            self::me()->get('domain_name') .
            self::makeUrl($controller, $action, $params, $anchor);
    }

    /**
     *
     * @param string $path
     * @return string
     */
    public static function makeLink($path = null)
    {
        return '/' . ltrim($path, '/');
    }

    /**
     *
     * @param string $path
     * @return string
     */
    public static function makeAbsLink($path = null)
    {
        return self::me()->get('protocol') . '://' .
            self::me()->get('domain_name') . '/' .
            ltrim($path, '/');
    }

    /**
     *
     * @param string $name
     * @return string
     */
    public static function getCSS($name)
    {
        $path = '/' . self::me()->get('css') . '/' . $name . self::ANALIB_EXT_CSS;
        return '<link rel="stylesheet" type="text/css" href="' . $path . '" />';
    }

    /**
     *
     * @param string $name
     * @return string
     */
    public static function getJavaScript($name)
    {
        $path = '/' . self::me()->get('js') . '/' . $name . self::ANALIB_EXT_JS;
        return '<script type="text/javascript" src="' . $path . '"></script>';
    }

    /**
     *
     * @param string $url
     */
    public static function redirect($url)
    {
        header('Location: ' . $url, true);
        exit;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        $out = array(
            'GET' => $this->getParams,
            'POST' => $this->postParams,
            'PATH' => $this->addressRow,
            'OUT' => $this->outParams,
            'OPTIONS' => $this->settings,
            'CONTROLLER NAME' => $this->controllerName,
            'ACTION NAME' => $this->actionName,
            'TEMPLATE NAME' => $this->template,
            'CONTROLLER' => $this->controller,
        );

        ob_start();
        DebugUtils::dump($out);
        $dump = ob_get_clean();
        if ($dump) {
            return $dump;
        }

        return '';

    }

}
