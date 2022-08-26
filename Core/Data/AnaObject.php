<?php

namespace analib\Core\Data;

/**
 *
 * @author acround
 */
abstract class AnaObject implements AnaObjectInterface
{
    protected static array $actuallyFields = [];
    protected array $fields = [];
    protected array $values = [];

    public function __construct()
    {
        $fields = $this->getFields();
        foreach ($fields as $name => $dbName) {
            $this->values[$name] = null;
        }
    }

    public function getClassName(): string
    {
        return get_class($this);
    }

    /**
     *
     * @return array
     */
    public function getFields(): array
    {
        if (count($this->fields) === 0) {
            $class = static::class;
            $this->fields = array_merge($this->fields, $class::$actuallyFields);
        }
        return $this->fields;
    }

    /**
     *
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     *
     * @param array $row
     * @return AnaObject
     */
    public function init(array $row = array()): AnaObject
    {
        foreach ($this->fields as $name => $dbName) {
            if (isset($row[$dbName])) {
                $setter = 'set' . $name;
                $this->$setter($row[$dbName]);
            }
        }
        return $this;
    }

}
