<?php

namespace analib\Helpers;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

abstract class InputHelper extends BaseHelper
{

    protected $id     = null;
    protected $name   = null;
    protected $class  = array();
    protected $params = array();

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param string $id
     * @return InputHelper
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     * @return InputHelper
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @param string $class
     * @return InputHelper
     */
    public function addClass($class)
    {
        if (!in_array($class, $this->class)) {
            $this->class[] = $class;
        }
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     *
     * @param string $class
     * @return InputHelper
     */
    public function setClass($class)
    {
        if (is_array($class)) {
            $this->class = $class;
        } else {
            $this->class = array($class);
        }
        return $this;
    }

    /**
     *
     * @param type $class
     * @return InputHelper
     */
    public function dropClass($class = null)
    {
        if ($class && ($class != 'date_input')) {
            $index = array_search($class, $this->class);
            if ($index !== false) {
                unset($this->class[$index]);
                $this->class = array_values(array_unique($this->class));
            }
        } else {
            $this->class = array('date_input');
        }
        return $this;
    }

    public function hasClass($class)
    {
        return in_array($class, $this->class);
    }

    public function getClassOut()
    {
        return implode(' ', $this->class);
    }

    /**
     *
     * @param strinf $name
     * @param string $value
     * @return InputHelper
     */
    public function addParam($name, $value)
    {
        switch ($name) {
            case 'id':
                $this->setId($value);
                break;
            case 'name':
                $this->setName($value);
                break;
            default :
                $this->params[$name] = $value;
                break;
        }
        return $this;
    }

    /**
     *
     * @param array $params
     * @return InputHelper
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return InputHelper
     */
    public function dropParam($name)
    {
        if (isset($this->params[$name])) {
            unset($this->params[$name]);
        }
        return $this;
    }

    /**
     *
     * @param string $name
     * @return boolean
     */
    public function hasParam($name)
    {
        return isset($this->params[$name]);
    }

    public function getParams()
    {
        return $this->params;
    }

}
