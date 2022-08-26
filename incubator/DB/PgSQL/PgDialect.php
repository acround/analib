<?php

namespace analib\incubator\DB\PgSQL;

use analib\incubator\DB\DBField;
use analib\incubator\DB\DBTable;
use analib\incubator\DB\DBValue;
use analib\incubator\DB\SQLDialect;

/**
 * Диалект MySQL
 *
 * @author acround
 */
class PgDialect extends SQLDialect
{

    protected $quoteName  = '"';
    protected $quoteValue = '\'';

    /**
     *
     * @return PgDialect
     */
    public static function create()
    {
        return new self();
    }

    public function quoteValue($value)
    {
        return $this->quoteV() . $value . $this->quoteV();
    }

    public function quoteName($name)
    {
        return $this->quoteN() . $name . $this->quoteN();
    }

    public function quoteField($field, $alias = null)
    {
        if ($field instanceof DBField) {
            $field = $field->getName();
            $field = explode('.', $field);
            foreach ($field as $i => $iValue) {
                $field[$i] = $this->quoteName($iValue);
            }
            $out = implode('.', $field);
        } elseif ($field instanceof DBValue) {
            $out = $this->quoteValue($field);
        } elseif (is_string($field)) {
            $field = explode('.', $field);
            foreach ($field as $i => $iValue) {
                $field[$i] = $this->quoteName($iValue);
            }
            $out = implode('.', $field);
        } else {
            $out = $this->quoteValue($field);
        }
        if ($alias) {
            $out .= ' AS ' . $this->quoteName($alias);
        }
        return $out;
    }

    public function quoteTable($table, $alias = null)
    {
        if ($table instanceof DBTable) {
            $table = $table->getName();
        }
        $table = explode('.', $table);
        foreach ($table as $i => $iValue) {
            $table[$i] = $this->quoteName($iValue);
        }
        $ret = implode('.', $table);
        if ($alias) {
            $ret .= ' AS ' . $this->quoteName($alias);
        }
        return $ret;
    }

}
