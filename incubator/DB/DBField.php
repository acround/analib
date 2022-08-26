<?php

namespace analib\incubator\DB;

/**
 * Description of DBField
 *
 * @author acround
 */
class DBField implements DialectString
{

    private string $name;
    private string $table;

    public function __construct($name, $table = null)
    {
        $this->name  = $name;
        $this->table = $table;
    }

    public function __toString()
    {
        return $this->name;
    }

    public static function create($name, $table = null)
    {
        return new self($name, $table);
    }

    public function toDialectString(SQLDialect $dialect)
    {
        $out = $dialect->quoteField($this->name);
        if ($this->table) {
            $out = $dialect->quoteField($this->table) . '.' . $out;
        }
        return $out;
    }

}
