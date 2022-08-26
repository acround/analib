<?php

namespace analib\incubator\DB;

/**
 * Description of DBValue
 *
 * @author acround
 */
class DBValue implements DialectString
{

    private $value = null;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function create($value)
    {
        return new self($value);
    }

    public function toDialectString(SQLDialect $dialect)
    {
        return $dialect->quoteValue($this->value);
    }

}
