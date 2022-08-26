<?php

namespace analib\incubator\DB;

/**
 * @author acround
 */
abstract class DBDao
{

    const DB_MYSQL    = 1;
    const DB_POSTGRES = 2;

    /**
     *
     * @var DBConnection
     */
    protected $connection = null;

    public function __construct(DBConnection $connection)
    {
        $this->connection = $connection;
    }

    abstract public function getTableName();

    abstract public function getById($id);

    abstract public function insert(DBObject $object);

    abstract public function delete(DBObject $object);

    abstract public function update(DBObject $object);

    abstract public function selectByString($select);

    abstract public function queryByString($select);
}
