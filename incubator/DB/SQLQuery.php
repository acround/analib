<?php

namespace analib\incubator\DB;

/**
 * SQL-запрос
 *
 * @author acround
 */
abstract class SQLQuery
{

    abstract public function toDialectString(SQLDialect $dialect);
}
