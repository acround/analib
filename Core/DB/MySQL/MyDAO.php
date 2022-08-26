<?php

namespace analib\Core\DB\MySQL;

use analib\Core\Application\Application;
use analib\Core\DB\DAO;
use analib\Core\DB\Resource;
use analib\Core\Exceptions\DBException;
use analib\Core\Exceptions\ObjectNotFoundException;
use analib\Util\DebugUtils;
use Exception;
use mysqli;

/**
 * Обмен с БД MySQL
 *
 * @author acround
 */
class MyDAO extends DAO
{

    public const TABLE_TYPE_BASE_TABLE = 'BASE TABLE';
    public const TABLE_TYPE_VIEW = 'VIEW';

    static private string $dbName;
    static private string $charset;
    static private mysqli $connect;
    static private array $instances;
    protected string $modelName;
    protected string $tableName;

    protected function __construct($modelName)
    {
        parent::__construct($modelName);
        /* @var $proto Resource */
        $modelName = ucfirst($modelName);
        if (class_exists($modelName)) {
            $this->modelName = $modelName;
            $proto = new $modelName(array());
            $this->tableName = $proto->getTable();
        }
    }

    public function getTable(): string
    {
        return $this->tableName;
    }

    /**
     *
     * @param string $modelName
     * @return MyDAO
     */
    public static function create(string $modelName): MyDAO
    {
        return new self($modelName);
    }

    /**
     * @throws DBException
     */
    public static function init($host = null, $user = null, $password = null, $database = null)
    {
        $config = Application::me()->get('db');
        $host = $host ?: $config['host'];
        $user = $user ?: $config['user'];
        $password = $password ?: $config['password'];
        $database = $database ?: $config['database'];
        $r = mysqli_connect($host, $user, $password);
        if ($r) {
            mysqli_select_db($r, $database);
            if (Application::me()->has('db_charset') && Application::me()->has('db_collate')) {
                mysqli_query($r, 'SET NAMES ' . Application::me()->get('db_charset') . ' COLLATE ' . Application::me()->get('db_collate'));
                self::$charset = Application::me()->get('db_charset');
            }
            self::$connect = $r;
            self::$dbName = $database;
        } else {
            throw new DBException('No DB connect');
        }
    }

    /**
     *
     * @return mysqli|null
     * @throws DBException
     */
    public static function getDB(): ?mysqli
    {
        if (!self::$connect) {
            self::init();
        }
        return self::$connect;
    }

    /**
     *
     * @return array
     * @throws DBException
     */
    public static function getTablesList(): array
    {
        $query = 'SHOW TABLES;';
        $r = mysqli_query(self::getDB(), $query);
        $tableList = array();
        while ($l = mysqli_fetch_array($r)) {
            $tableList[] = $l[0];
        }
        return $tableList;
    }

    public static function getTableInfo($table)
    {
        /*
          TABLE_CATALOG
          TABLE_SCHEMA
          TABLE_NAME
          TABLE_TYPE
          ENGINE
          VERSION
          ROW_FORMAT
          TABLE_ROWS
          AVG_ROW_LENGTH
          DATA_LENGTH
          MAX_DATA_LENGTH
          INDEX_LENGTH
          DATA_FREE
          AUTO_INCREMENT
          CREATE_TIME
          UPDATE_TIME
          CHECK_TIME
          TABLE_COLLATION
          CHECKSUM
          CREATE_OPTIONS
          TABLE_COMMENT
         */
        mysqli_query(self::$connect, "SET NAMES 'utf8_general_ci'");
        mysqli_query(self::$connect, "SET CHARACTER SET 'utf8_general_ci'");
        $query = sprintf("SELECT * FROM `information_schema`.`tables` WHERE `table_schema` = '%s' and `table_name` = '%s'", self::$dbName, $table);
        $r = mysqli_query(self::$connect, $query);
        return mysqli_fetch_assoc($r);
    }

    public static function getColumnsList($table): array
    {
        /**
         * Field
         * Type
         * Null
         * Key
         * Default
         * Extra
         */
        $query = 'SHOW COLUMNS FROM `' . $table . '`;';
        $r = mysqli_query(self::$connect, $query);
        $fields = array();
        if ($r) {
            while ($l = mysqli_fetch_assoc($r)) {
                $fields[] = $l;
            }
        }
        return $fields;
    }

    public static function getColumnsInfo($table): array
    {
        /**
         * TABLE_CATALOG
         * TABLE_SCHEMA
         * TABLE_NAME
         * COLUMN_NAME
         * ORDINAL_POSITION
         * COLUMN_DEFAULT
         * IS_NULLABLE
         * DATA_TYPE
         * CHARACTER_MAXIMUM_LENGTH
         * CHARACTER_OCTET_LENGTH
         * NUMERIC_PRECISION
         * NUMERIC_SCALE
         * DATETIME_PRECISION
         * CHARACTER_SET_NAME
         * COLLATION_NAME
         * COLUMN_TYPE
         * COLUMN_KEY
         * EXTRA
         * PRIVILEGES
         * COLUMN_COMMENT
         */
        $query = "SELECT * FROM `information_schema`.`columns` WHERE `table_schema` = '" . self::$dbName . "' and `table_name` = '" . $table . "'";
        $r = mysqli_query(self::$connect, $query);
        $info = array();
        while ($l = mysqli_fetch_assoc($r)) {
            $info[$l['COLUMN_NAME']] = $l;
        }
        return $info;
    }

    public static function getTablesStatus(): array
    {
        /**
         * Name
         * Engine
         * Version
         * Row_format
         * Rows
         * Avg_row_length
         * Data_length
         * Max_data_length
         * Index_length
         * Data_free
         * Auto_increment
         * Create_time
         * Update_time
         * Check_time
         * Collation
         * Checksum
         * Create_options
         * Comment
         */
        $query = 'SHOW TABLE STATUS';
        $r = mysqli_query(self::$connect, $query);
        $info = array();
        while ($l = mysqli_fetch_assoc($r)) {
            $info[$l['Name']] = $l;
        }
        return $info;
    }

    public static function getForeignKeys($table): array
    {
        $query = 'SELECT * '
            . 'FROM `information_schema`.`KEY_COLUMN_USAGE` '
            . 'WHERE `CONSTRAINT_SCHEMA` LIKE \'' . self::$dbName . '\' AND '
            . '`REFERENCED_TABLE_SCHEMA` LIKE \'' . self::$dbName . '\' AND '
            . '`TABLE_NAME` LIKE \'' . $table . '\';';
        $r = mysqli_query(self::$connect, $query);
        $constraints = array();
        while ($l = mysqli_fetch_assoc($r)) {
            $constraints[$l['COLUMN_NAME']] = $l['REFERENCED_TABLE_NAME'] . '.' . $l['REFERENCED_COLUMN_NAME'];
        }
        return $constraints;
    }

    /**
     *
     * @param string $modelName
     * @return MyDAO
     */
    public static function getInstance($modelName): MyDAO
    {
        if (!isset(self::$instances[$modelName])) {
            try {
                self::$instances[$modelName] = self::create($modelName);
            } catch (Exception $ex) {
                DebugUtils::dump($modelName);
            }
        }
        return self::$instances[$modelName];
    }

    public function createModel(): Resource
    {
        /* @var $proto Resource */
        return new $this->modelName(array());
    }


    /**
     * @throws DBException
     */
    public static function query($query)
    {
        $db = self::getDB();
        if ($db) {
            return mysqli_query($db, $query);
        }
        return null;
    }

    /**
     * @throws DBException
     */
    public static function getRawByQuery($query)
    {
        $db = self::getDB();
        if ($db) {
            $r = mysqli_query($db, $query);
            if ($r) {
                return mysqli_fetch_array($r);
            }
        }

        return null;
    }

    /**
     * @throws DBException
     */
    public static function getRawListByQuery($query): array
    {
        $db = self::getDB();
        if ($db) {
            $r = mysqli_query($db, $query);
            if ($r) {
                $res = array();
                while ($l = mysqli_fetch_array($r)) {
                    $res[] = $l;
                }
                return $res;
            }
        }
        return array();
    }

    /**
     * @throws DBException
     * @throws ObjectNotFoundException
     */
    public function getByQuery($query)
    {
        $db = self::getDB();
        if ($db) {
            $r = mysqli_query($db, $query);
            if ($r) {
                if ($l = mysqli_fetch_assoc($r)) {
                    $className = $this->modelName;
                    return new $className($l);
                }

                throw new ObjectNotFoundException('No rows. Query: "' . $query . '"');
            } else {
                throw new DBException(mysqli_error($db) . ' Query:' . $query, mysqli_errno($db));
            }
        }
        return null;
    }

    /**
     *
     * @param string $query
     * @return array
     * @throws DBException
     */
    public function getListByQuery($query): ?array
    {
        $db = self::getDB();
        $ret = array();
        if ($db) {
            $r = mysqli_query($db, $query);
            if ($r) {
                $className = $this->modelName;
                while ($l = mysqli_fetch_assoc($r)) {
                    $ret[] = new $className($l);
                }
            } else {
                throw new DBException(mysqli_error($db));
            }
            return $ret;
        }
        return null;
    }

    /**
     *
     * @param int $id
     * @return Resource|null
     */
    public function getById($id): ?Resource
    {
        $table = $this->getTable();
        $query = 'SELECT * FROM `' . $table . '` WHERE `id` = ' . $id;
        try {
            return $this->getByQuery($query);
        } catch (DBException | ObjectNotFoundException $e) {
        }
        return null;
    }

    /**
     * @throws DBException
     */
    public static function save(Resource $model)
    {
        $db = self::getDB();
        $tableName = $model->getTable();
        $id = $model->getId();
        $fields = array();
        foreach ($model->getContent() as $field => $value) {
            $fields[] = '`' . $field . '` = ' . "'" . mysqli_real_escape_string($db, $value) . "'";
        }
        $query = 'UPDATE `' . $tableName . '` SET ' . implode(', ', $fields) . ' WHERE `id` = ' . $id;
        self::query($query);
        $errno = mysqli_errno($db);
        if ($errno) {
            throw new DBException(mysqli_error($db), mysqli_errno($db));
        }
    }

    /**
     * @param Resource $model
     * @throws DBException
     */
    public static function add(Resource $model)
    {
        $db = self::getDB();
        $tableName = $model->getTable();
        $fields = array();
        $values = array();
        foreach ($model->getContent() as $field => $value) {
            $fields[] = '`' . $field . '`';
            $values[] = ($value === null) ? 'NULL' :
                (($value === true) ? 1 :
                    (($value === false) ? 0 :
                        ("'" . mysqli_real_escape_string($db, $value) . "'")));
        }
        $query = 'INSERT INTO `' . $tableName . '` (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
        mysqli_query($db, $query);
        $id = mysqli_insert_id($db);
        if ($id) {
            $model->setId($id);
        } else {
            throw new DBException(mysqli_error($db), mysqli_errno($db));
        }
    }

    /**
     * @throws DBException
     */
    public static function take(Resource $model)
    {
        if ($model->getId()) {
            self::save($model);
        } else {
            self::add($model);
        }
    }

    /**
     * @throws DBException
     */
    public static function delete(Resource $model)
    {
        $tableName = $model->getTable();
        $query = 'DELETE FROM `' . $tableName . '` WHERE `id` = ' . $model->getId();
        $db = self::getDB();
        mysqli_query($db, $query);
        $model->setId(0);
    }

}
