<?php

namespace analib\incubator\DB;

/**
 * @author acround
 */
abstract class SQLDialect
{

    protected $quoteName  = '';
    protected $quoteValue = '\'';

//	abstract public function table($table, $alias);
//	abstract public function field($table, $alias);

    protected function quoteN()
    {
        return $this->quoteName;
    }

    protected function quoteV()
    {
        return $this->quoteValue;
    }

    abstract public function quoteValue($value);

    abstract public function quoteField($field, $alias = null);

    abstract public function quoteTable($table, $alias = null);
}
