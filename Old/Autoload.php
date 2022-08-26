<?php

namespace analib\Old;

/**
 * Description of Autoload
 *
 * @author acround
 */
class Autoload
{

    /**
     * @var Autoload
     */
    static protected $instance = null;

    final protected function __construct()
    {

    }

    /**
     *
     * @return Autoload
     */
    public static function me()
    {
        if (!self::$instance) {
            self::$instance = new Autoload();
        }
        return self::$instance;
    }

    public function getPathArray()
    {
        return explode(PATH_SEPARATOR, get_include_path());
    }

    /**
     *
     * @param array $pathArray
     */
    public function setPathArray(array $pathArray)
    {
        set_include_path(implode(PATH_SEPARATOR, $pathArray));
    }

    /**
     *
     * @param string $folder
     * @return \Autoload
     */
    public function addFolder($folder)
    {
        if (is_string($folder) && file_exists($folder) && is_dir($folder)) {
            $pathArray = array_unique($this->getPathArray());
            if (!in_array($folder, $pathArray)) {
                $pathArray[] = $folder;
                $this->setPathArray($pathArray);
            }
        }
        return $this;
    }

    /**
     *
     * @param string $folder
     * @return \Autoload
     */
    public function delFolder($folder)
    {
        if (is_string($folder) && file_exists($folder) && is_dir($folder)) {
            $pathArray = array_unique($this->getPathArray());
            $index     = array_search($folder, $pathArray);
            if (index !== false) {
                unset($pathArray[$index]);
                $this->setPathArray($pathArray);
            }
        }
        return $this;
    }

}
