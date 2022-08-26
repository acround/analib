<?php

namespace analib\Helpers;

/**
 * Description of ExceptionPrint
 *
 * @author acround
 */
class ExceptionPrint extends BaseHelper
{

    const NAME = 'exception';

    /**
     * @return ExceptionPrint
     */
    public static function create()
    {
        return new self();
    }

    public function addException(BaseException $e)
    {
        $this->variables[self::NAME] = $e;
        return $this;
    }

    public function run()
    {
        if (
            (\analib\Core\Application\Application::me()->getDebug() & E_ERROR) &&
            isset($this->variables[self::NAME])
        ) {
            require $this->getTemplate();
        }
        return $this;
    }

}
