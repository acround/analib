<?php

namespace analib\incubator\DB\MySQL;

use analib\Core\Exceptions\ObjectNotFoundException;
use analib\incubator\DB\DBDao;
use analib\incubator\DB\DBObject;

/**
 * @author acround
 */
class MySQLDao extends DBDao
{

    protected $tableName = null;
    protected $errno     = 0;
    protected $error     = '';

    /**
     * @param MySQLConnection $connection
     * @return MySQLDao
     */
    public static function create(MySQLConnection $connection)
    {
        return new self($connection);
    }

    protected function name2php($name)
    {
        $ret = explode('_', $name);
        foreach ($ret as $n => $part) {
            $ret[$n] = ucfirst($part);
        }
        return implode('', $ret);
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getTableNamePhp()
    {
        return $this->name2php($this->tableName);
    }

    public function getById($id)
    {
        $result      = null;
        $query       = 'select * from `' . $this->getTableName() . '` where `id` = ' . $id;
        $r           = mysqli_query($query, $this->connection);
        $this->errno = mysqli_errno($this->connection);
        if ($this->errno) {
            $this->error = mysqli_error($this->connection);
        } else {
            $tn  = $this->getTableNamePhp();
            while ($row = mysqli_fetch_assoc($r)) {
                $result[] = $tn::create($row);
            }
            if (count($result)) {
                $result = $result[0];
            } else {
                throw new ObjectNotFoundException();
            }
        }
        return $result;
    }

    public function insert(DBObject $object)
    {

    }

    /**
     * @param DBObject $object
     * @return $this
     */
    public function delete(DBObject $object)
    {
        $query = 'delete from ' . $this->getTableName() . ' where `id` = ' . $object->getId();
        return $this->queryByString($query);
    }

    public function update(DBObject $object)
    {

    }

    public function selectByString($query)
    {
        $result      = array();
        $r           = mysqli_query($query, $this->connection);
        $this->errno = mysqli_errno($this->connection);
        if ($this->errno) {
            $this->error = mysqli_error($this->connection);
        } else {
            $tn  = $this->getTableNamePhp();
            while ($row = mysqli_fetch_assoc($r)) {
                $result[] = $tn - create($row);
            }
        }
        return $result;
    }

    public function queryByString($query)
    {
        $r = mysqli_query($query, $this->connection);
        return $this;
    }

}
