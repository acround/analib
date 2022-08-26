<?php

namespace analib\Core\Build\MySQL;

use analib\Core\DB\MySQL\MyDAO;
use analib\Core\Exceptions\DBException;
use DOMDocument;

/**
 *
 * @author acround
 */
class MyMetaMaker
{

    protected $dbName;
    protected $host;
    protected $charset         = 'utf8_general_ci';
    protected $tableList       = array();
    protected $tableFields     = array();
    protected $tableForKeys    = array();
    protected $tableInfo       = array();
    protected $tableStatus     = array();
    protected $tableFieldsInfo = array();
    protected $types           = array(
        'int'                => 'int',
        'int unsigned'       => 'int',
        'tinyint'            => 'int',
        'tinyint unsigned'   => 'int',
        'smallint'           => 'int',
        'smallint unsigned'  => 'int',
        'mediumint'          => 'int',
        'bigint'             => 'int',
        'boolean'            => 'int',
        'decimal'            => 'float',
        'float'              => 'float',
        'float unsigned'     => 'float',
        'double'             => 'float',
        'real'               => 'float',
        'varchar'            => 'string',
        'text'               => 'string',
        'tinytext'           => 'string',
        'mediumtext'         => 'string',
        'longtext'           => 'string',
        'char'               => 'string',
        'binary'             => 'string',
        'varbinary'          => 'string',
        'bit'                => 'boolean',
        'serial'             => 'int',
        'date'               => 'date',
        'datetime'           => 'datetime',
        'time'               => 'time',
        'timestamp'          => 'Timestamp',
        'year'               => 'date',
        'enum'               => 'enum',
        'set'                => 'set',
        'tinyblob'           => 'blob',
        'mediumblob'         => 'blob',
        'blob'               => 'blob',
        'longblob'           => 'blob',
        'geometry'           => 'Geometry',
        'point'              => 'Point',
        'linestring'         => 'LineString',
        'polygon'            => 'Polygon',
        'multipoint'         => 'MultiPoint',
        'multilinestring'    => 'MultilineString',
        'multipolygon'       => 'MultiPolygon',
        'geometrycollection' => 'GeometryCollection',
    );

    public static function field2class($field)
    {
        $className = explode('_', $field);
        foreach ($className as $i => $iValue) {
            $className[$i] = ucfirst($iValue);
        }
        return implode('', $className);
    }

    /**
     * @throws DBException
     */
    public function __construct($host, $user, $password, $database = null, $charset = null)
    {
        MyDAO::init($host, $user, $password, $database);
        $this->charset = $charset;
        $this->dbName  = $database;
        $this->host    = $host;
    }

    /**
     * @throws DBException
     */
    public static function create($host, $user, $password, $database = null, $charset = null)
    {
        return new self($host, $user, $password, $database, $charset);
    }

    /**
     *
     * @return array
     * @throws DBException
     */
    public function tablesList()
    {
        if (count($this->tableList) === 0) {
            $this->tableList = MyDAO::getTablesList();
        }
        return $this->tableList;
    }

    /**
     *
     * @param string $table
     * @return array of
     * Field
     * Type
     * Null
     * Key
     * Default
     * Extra
     */
    public function describe($table)
    {
        $this->tableInfo[$table]       = MyDAO::getTableInfo($table);
        $this->tableStatus             = MyDAO::getTablesStatus();
        $this->tableForKeys[$table]    = MyDAO::getForeignKeys($table);
        $this->tableFields[$table]     = MyDAO::getColumnsList($table);
        $this->tableFieldsInfo[$table] = MyDAO::getColumnsInfo($table);
        return $this->tableFields[$table];
    }

    public function tableFields($table)
    {
        return (isset($this->tableFields[$table]) ? $this->tableFields[$table] : array());
    }

    public function tableInfo($table)
    {
        return (isset($this->tableInfo[$table]) ? $this->tableInfo[$table] : array());
    }

    public function tableForKeys($table)
    {
        return (isset($this->tableForKeys[$table]) ? $this->tableForKeys[$table] : array());
    }

    /**
     *
     * @return DOMDocument
     * @throws DBException
     */
    public function makeXml()
    {
        $xml               = new DOMDocument('1.0', $this->charset);
        $xml->formatOutput = true;
        $classes           = $xml->appendChild($xml->createElement('classes'));
        foreach ($this->tablesList() as $table) {
            $this->describe($table);
            if ($this->tableInfo[$table]['TABLE_TYPE'] === 'VIEW') {
                continue; // Представление
            }
            $class     = $xml->createElement('class');
            $className = self::field2class($table);
            $class->setAttribute('name', $className);
            $class->setAttribute('table', $table);
            $title     = $this->tableInfo[$table]['TABLE_COMMENT'];
//			if (strtolower($this->charset) != 'utf-8') {
//				$title = iconv($this->charset, 'utf-8', $title);
//			}
            $class->setAttribute('title', $title);
            $class->setAttribute('type', $this->tableInfo[$table]['TABLE_TYPE']);
            $class->setAttribute('collation', $this->tableStatus[$table]['Collation']);
            $classes->appendChild($class);
//			$info = $xml->createElement('info');
//			$info->setAttribute('type', $this->tableInfo[$table]['TABLE_TYPE']);
//			$class->appendChild($info);
            foreach ($this->tableFields[$table] as $field) {
                if ($field['Key'] === 'PRI') {
                    $prop = $xml->createElement('identifier');
                } else {
                    $prop = $xml->createElement('property');
                }
                $prop->setAttribute('dbname', $field['Field']);
                $propName = explode('_', $field['Field']);
                foreach ($propName as $i => $iValue) {
                    $propName[$i] = ucfirst($iValue);
                }
                $propName = implode('', $propName);
                $prop->setAttribute('propname', $propName);
                $isId     = substr($field['Field'], -3) === '_id';
                if ($isId) {
                    $clearName      = substr($field['Field'], 0, -3);
                    $clearClassName = self::field2class(substr($field['Field'], 0, -3));
                } else {
                    $clearName      = $clearClassName = $field['Field'];
                }
                $type = explode('(', trim($field['Type'], ')'));
                $prop->setAttribute('type', $this->types[$type[0]]);
                if (isset($this->tableForKeys($table)[$field['Field']])) {
                    $fk        = explode('.', $this->tableForKeys($table)[$field['Field']]);
                    $className = self::field2class($fk[0]);
                    $prop->setAttribute('class', $className);
                    $prop->setAttribute('object', $clearClassName);
                } elseif ($isId && in_array($clearName, $this->tablesList(), true)) {
                    $prop->setAttribute('class', self::field2class($clearClassName));
                    $prop->setAttribute('object', $clearClassName);
                } else {
                    if ($isId) {
                        $prop->setAttribute('class', '');
                    }
                    if (count($type) > 1) {
                        $prop->setAttribute('length', $type[1]);
                    }
                }
                if ($field['Default'] !== null) {
                    $default = ($field['Default'] === null) ? 'null' : $field['Default'];
                    if ($this->types[$type[0]] === 'boolean') {
                        $default = trim($default, "b'");
                    }
                    $prop->setAttribute('default', $default);
                } elseif ($field['Null'] === 'YES') {
                    $prop->setAttribute('default', 'null');
                }
                if ($field['Null'] === 'YES') {
                    $prop->setAttribute('null', 'yes');
                } else {
                    $prop->setAttribute('null', 'no');
                }
                $fieldTitle = trim($this->tableFieldsInfo[$table][$field['Field']]['COLUMN_COMMENT']);
                if ($fieldTitle && strtolower($this->charset) !== 'utf-8') {
                    $fieldTitle = iconv($this->charset, 'utf-8', $fieldTitle);
                }
                $prop->setAttribute('title', $fieldTitle);
                $class->appendChild($prop);
            }
        }
        return $xml;
    }

}
