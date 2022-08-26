<?php

namespace analib\Util;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class SafeArray implements \ArrayAccess
{

    private $array = array();

    /**
     *
     * @param array $array
     * @return SafeArray
     */
    public static function create(array $array = array())
    {
        $obj = new self();
        return $obj->init($array);
    }

    /**
     *
     * @param array $array
     * @return \SafeArray
     */
    public function init(array $array = array())
    {
        $this->array = $array;
        return $this;
    }

    /**
     *
     * @param string $name
     * @param type $value
     * @return \SafeArray
     */
    public function set($name, $value)
    {
        $this->array[$name] = $value;
        return $this;
    }

    public function get($name)
    {
        if (isset($this->array[$name])) {
            return $this->array[$name];
        } else {
            return null;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset($this->array[$offset])) {
            return $this->array[$offset];
        } else {
            return null;
        }
    }

    public function offsetSet($offset, $value)
    {
        $this->array[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (isset($this->array[$offset])) {
            unset($this->array[$offset]);
        }
    }

}
