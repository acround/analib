<?php

/**
 * Description of FB2Sequence
 *
 * @author acround
 */

namespace analib\Core\Xml\Fb2;

use ArrayAccess;

class FB2Sequence implements ArrayAccess
{

    private $name = null;
    private $number = null;

    /**
     *
     * @param array $values
     * @return FB2Sequence
     */
    public static function create(array $values = null)
    {
        $r = new self();
        if (isset($values['name'])) {
            $r->setName(htmlspecialchars($values['name']));
        }
        if (isset($values['number'])) {
            $r->setNumber($values['number']);
        }
        return $r;
    }

    /**
     *
     * @param string $name
     * @return FB2Sequence
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName($full = false)
    {
        return $full ? htmlspecialchars_decode($this->name) : trim(htmlspecialchars_decode($this->name));
    }

    public function getRawName($full = false)
    {
        return $this->name;
    }

    /**
     *
     * @param string $number
     * @return FB2Sequence
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function __toString()
    {
        if ($this->getName()) {
            if ($this->getNumber()) {
                return $this->getName(true) . ':' . $this->number;
            }

            return $this->getName(true);
        }

        return '';
    }

    public function offsetExists($offset)
    {
        return ($offset === 'name') || ($offset === 'number');
    }

    public function offsetGet($offset)
    {
        switch ($offset) {
            case 'name':
                return $this->getName();
            case 'number':
                return $this->getNumber();
            default :
                return null;
        }
    }

    public function offsetSet($offset, $value)
    {
        switch ($offset) {
            case 'name':
                $this->setName($value);
            case 'number':
                return $this->setNumber($value);
        }
        return $this;
    }

    public function offsetUnset($offset)
    {
        switch ($offset) {
            case 'name':
                $this->setName('');
            case 'number':
                return $this->setNumber('');
        }
        return $this;
    }

}
