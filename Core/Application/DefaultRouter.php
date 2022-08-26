<?php

namespace analib\Core\Application;

use analib\Core\Exceptions\HTTPException;
use analib\Core\Exceptions\PageNotFoundException;
use analib\Controller\BaseController;

/**
 * Роутор по умолчанию
 *
 * @author acround
 */
class DefaultRouter extends BaseRouter
{

    /**
     *
     * @return IRouterInterface
     */
    public static function create()
    {
        return new self();
    }

    /**
     * @throws PageNotFoundException
     */
    public function route()
    {
        $addressRow = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $address    = explode('?', $addressRow);
//        if (count($address) > 1) {
//            $addressParams = explode('&', $address[1]);
//        } else {
//            $addressParams = array();
//        }
        $address        = explode('/', trim($address[0], '/'));
        $controllerName = $address[0];
        if (!$controllerName) {
            $controllerName = Application::DEFAULT_CONTROLLER;
        }

        $controllerNameFull = '\\' . $controllerName . ANALIB_SUF_CONTROLLER;

        try {
            if (class_exists($controllerNameFull)) {
                $controller = new $controllerNameFull();
                array_shift($address);
            } else {
                throw new PageNotFoundException($controllerNameFull . ' - class not found');
            }
        } catch (HTTPException $e) {
            throw $e;
        }

        if (count($address)) {
            $action     = $address[0];
        } else {
            $action     = BaseController::DEFAULT_ACTION;
        }
        $actionName = $action . ANALIB_SUF_ACTION;

        if (method_exists($controller, $actionName)) {
            array_shift($address);
        } else {
            throw new PageNotFoundException();
        }

        Application::me()->
            setControllerName($controllerName)->
            setAction($action)->
            setActionName($actionName)->
            setController($controller)->
            setGetParams($_GET)->
            setPostParams($_POST)->
            setAddressRow($address);
    }

    public function makeUrl($controller = null, $action = null, array $params = null, $anchor = null)
    {
        $action     = ($action === BaseController::DEFAULT_ACTION) ? '' : $action;
        $controller = trim($controller, '/');
        if (!$action) {
            $controller = ($controller === BaseController::DEFAULT_CONTROLLER) ? '' : $controller;
        }
        $url = '/';
        if ($controller) {
            $url .= $controller . '/';
            if ($action) {
                $url .= $action . '/';
            }
        }
        if ($params) {
            $tmpExt = array();
            $tmp    = array();
            foreach ($params as $key => $value) {
                if (is_int($key)) {
                    $tmpExt[] = $value;
                } else {
                    $tmp[] = $key . '=' . $value;
                }
            }
            $url .= implode('/', $tmpExt);
            if (count($tmp)) {
                $url .= '?' . implode('&', $tmp);
            }
        }
        if ($anchor) {
            $url .= '#' . $anchor;
        }
        return $url;
    }

}
