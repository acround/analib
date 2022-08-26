<?php

namespace analib\Core\Application;

/**
 *
 * @author acround
 */
interface IRouterInterface
{

    public function route();

    public function makeUrl($controller = null, $action = null, array $params = null, $anchor = null);
}
