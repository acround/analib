<?php

namespace analib\incubator\DB;

/**
 * Description of DBTable
 *
 * @author acround
 */
class DBTable implements DialectString
{

    private string $name;
    private string $alias;

    public function __construct($name, $alias = null)
    {
        $this->name = $name;
        $this->alias = $alias;
    }

    public static function create($name, $alias = null)
    {
        return new self($name, $alias);
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function toDialectString(SQLDialect $dialect)
    {
        $out = $dialect->quoteTable($this->name, $this->alias);
        return $out;
    }

}
