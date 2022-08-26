<?php

namespace analib\Core\Data;

use analib\Core\Exceptions\PropertyNotFoundException;
use ReflectionClass;
use ReflectionException;

/**
 *
 * @author acround
 */
class ArrayLike implements IArrayLike
{

    /**
     * @throws ReflectionException
     */
    public function keys(): array
    {
        $class = new ReflectionClass(get_class($this));
        $names = array();
        foreach ($class->getProperties() as $property) {
            $name = $property->getName();
            if ($property->isPublic() && !$property->isStatic()) {
                $names[] = $name;
            }
        }
        return $names;
    }

    /**
     * @throws ReflectionException
     */
    public function values(): array
    {
        $class = new ReflectionClass(get_class($this));
        $names = array();
        foreach ($class->getProperties() as $property) {
            $name = $property->getName();
            if ($property->isPublic() && !$property->isStatic()) {
                $names[$name] = $this->{$name};
            }
        }
        return $names;
    }

    /**
     * @throws ReflectionException
     */
    public function offsetExists($offset): bool
    {
        return in_array($offset, $this->keys(), true);
    }

    /**
     * @throws ReflectionException
     * @throws PropertyNotFoundException
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->{$offset};
        }

        throw new PropertyNotFoundException();
    }

    public function offsetSet($offset, $value)
    {

    }

    public function offsetUnset($offset)
    {

    }

}
