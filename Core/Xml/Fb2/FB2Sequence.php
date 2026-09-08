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
    public static function create(?array $values = null)
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

    public function offsetExists($offset): bool
    {
        return ($offset === 'name') || ($offset === 'number');
    }

    public function offsetGet($offset): mixed
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

    public function offsetSet($offset, $value): void
    {
        switch ($offset) {
            case 'name':
                $this->setName($value);
                break;
            case 'number':
                $this->setNumber($value);
                break;
        }
    }

    public function offsetUnset($offset): void
    {
        switch ($offset) {
            case 'name':
                $this->setName('');
                break;
            case 'number':
                $this->setNumber('');
                break;
        }
    }

}
