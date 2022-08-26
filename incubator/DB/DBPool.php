<?php

namespace analib\incubator\DB;

/**
 * Description of DBPool
 *
 * @author acround
 */
class DBPool
{

    static protected $instance = null;

    /**
     *
     * @var DBConnection
     */
    static private $defaultConnection = null;
    static private $connections       = array();

    protected function __construct()
    {

    }

    public function __destruct()
    {
        if (self::$defaultConnection) {
            self::$defaultConnection->close();
        }
        /* @var $conn DBConnection */
        foreach (self::$connections as $conn) {
            if ($conn)
                $conn->close();
        }
    }

    public static function add(DBConnection $db, $name = null)
    {
        if ($name === null) {
            self::$defaultConnection = $db;
        } else {
            self::$connections[$name] = $db;
        }
    }

    public static function get($name = null)
    {
        if ($name === null) {
            return self::$defaultConnection;
        } elseif (isset(self::$connections[$name])) {
            return self::$connections[$name];
        } else {
            throw new \Exception($name . ' - db not found.', 404);
        }
    }

}
