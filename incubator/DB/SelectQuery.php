<?php

namespace analib\incubator\DB;

/**
 * SQL-запрос на выборку
 *
 * @author acround
 */
class SelectQuery extends SQLQuery
{

    protected $fields     = array();
    protected $where      = array();
    protected $whereLogic = array();
    protected $aliases    = array();
    protected $table      = array();
    protected $limit      = null;
    protected $offset     = null;

    /**
     *
     * @return SelectQuery
     */
    public static function create()
    {
        return new self();
    }

    /**
     *
     * @param type $field
     * @param type $alias
     * @return SelectQuery
     */
    public function get($field, $alias = null)
    {
        $this->fields[] = array(
            'name'  => $field,
            'alias' => $alias
        );
        return $this;
    }

    /**
     *
     * @param type $table
     * @return SelectQuery
     */
    public function from($table, $alias = null)
    {
        $this->table[] = array(
            'name'  => $table,
            'alias' => $alias
        );
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
    }

    /**
     *
     * @param SQLDialect $dialect
     * @return type
     */
    public function toDialectString(SQLDialect $dialect = null)
    {
        $out = 'SELECT';
        $get = array();
        foreach ($this->fields as $field) {
            if ($dialect) {
                $get[] = $dialect->quoteField($field['name'], $field['alias']);
            } else {
                if ($field['alias']) {
                    $get[] = $field['name'] . ' AS ' . $field['alias'];
                } else {
                    $get[] = $field['name'];
                }
            }
        }
        $out .= ' ' . implode(', ', $get);
        if (count($this->table)) {
            $from = array();
            foreach ($this->table as $table)
                if ($table['name'] instanceof DBTable) {
                    if ($table['alias']) {
                        $table['name']->setAlias($table['alias']);
                    }
                    $from[] = $table['name']->toDialectString($dialect);
                } else {
                    if ($field['alias']) {
                        $from[] = $table['name'] . ' AS ' . $table['alias'];
                    } else {
                        $from[] = $table['name'];
                    }
                }
            $out .= ' FROM ' . implode(', ', $from);
        }
        return $out;
    }

}
