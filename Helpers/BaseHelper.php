<?php

namespace analib\Helpers;

/**
 * Description of BaseHelper
 *
 * @author acround
 */
abstract class BaseHelper
{

    protected $view_dir  = null;
    protected $variables = array();

    public function __construct()
    {
        $className = get_class($this);
        if (file_exists(ANALIB_DIR_SELF_HELPER_TEMPLATE . DIRECTORY_SEPARATOR . $className . ANALIB_EXT_HELPER_VIEW)) {
            $this->view_dir = ANALIB_DIR_SELF_HELPER_TEMPLATE;
        } elseif (file_exists(ANALIB_DIR_LIB_HELPER_TEMPLATE . DIRECTORY_SEPARATOR . $className . ANALIB_EXT_HELPER_VIEW)) {
            $this->view_dir = ANALIB_DIR_LIB_HELPER_TEMPLATE;
        } else {
            $this->view_dir = \analib\Core\Application\Application::me()->get('helper_view_dir');
        }
    }

    /**
     *
     * @param string $dir
     * @return BaseHelper
     */
    public function setViewDir($dir)
    {
        $this->view_dir = $dir;
        return $this;
    }

    /**
     *
     * @param string $variable
     * @param mixed $value
     * @return BaseHelper
     */
    public function setVariable($variable, $value)
    {
        $this->variables[$variable] = $value;
        return $this;
    }

    public function getVariable($variable)
    {
        if (isset($this->variables[$variable])) {
            return $this->variables[$variable];
        } else {
            return null;
        }
    }

    public function run()
    {
        extract($this->variables);
        require $this->getTemplate();
        return $this;
    }

    public function dump()
    {
        ob_start();
        $this->run();
        return ob_get_clean();
    }

    /**
     *
     * @return string
     */
    protected function getTemplate()
    {
        $templateName = $this->view_dir . DIRECTORY_SEPARATOR . get_class($this) . ANALIB_EXT_VIEW;
        return $templateName;
    }

}
