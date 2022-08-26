<?php

namespace analib\Core\Application;

/**
 * Description of BaseRouter
 *
 * @author acround
 */
abstract class BaseRouter implements IRouterInterface
{

    abstract public function route();

    abstract public function makeUrl($controller = null, $action = null, array $params = null, $anchor = null);
}
