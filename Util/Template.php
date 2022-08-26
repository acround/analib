<?php

namespace analib\Util;

use analib\Core\Application\Application;
use analib\Core\Exceptions\AutoLoadException;

/**
 * Description of Template
 *
 * @author acround
 */
class Template extends StaticFactory
{

    public static function hasTemplate($name)
    {
        return file_exists(Application::me()->get('template_dir') . DIRECTORY_SEPARATOR . $name . ANALIB_EXT_VIEW);
    }

    public static function hasTemplateLib($name)
    {
        return file_exists(Application::me()->get('template_dir_lib') . DIRECTORY_SEPARATOR . $name . ANALIB_EXT_VIEW);
    }

    public static function getTemplatePath($name)
    {
        return Application::me()->get('template_dir') . DIRECTORY_SEPARATOR . $name . ANALIB_EXT_VIEW;
    }

    public static function getTemplatePathLib($name)
    {
        return Application::me()->get('template_dir_lib') . DIRECTORY_SEPARATOR . $name . ANALIB_EXT_VIEW;
    }

    static function includeTemplate($name)
    {
        if (self::hasTemplate($name)) {
            return self::getTemplatePath($name);
        }

        if (self::getTemplatePathLib($name)) {
            return self::getTemplatePathLib($name);
        } else {
            throw new AutoLoadException($name . ' - file not found.');
        }
    }

    static function includeActionTemplate()
    {
        $path = self::includeTemplate(Application::me()->getControllerName() . DIRECTORY_SEPARATOR . Application::me()->getAction());
        if (!file_exists($path)) {
            $path = self::includeTemplate('notemplate');
        }
        return $path;
    }

}
