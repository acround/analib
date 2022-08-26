<?php

namespace analib\incubator\DB;

/**
 *
 * @author acround
 */
interface DialectString
{

    public function toDialectString(SQLDialect $dialect);
}
