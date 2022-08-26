<?php

namespace analib\incubator\DB\MySQL;

use analib\Core\Exceptions\DBException;
use analib\incubator\DB\DBConnection;

/**
 * Description of MySQLConnection
 *
 * @author acround
 */
class MySQLConnection extends DBConnection
{

    protected $opened = false;

    public function __construct($host, $user = null, $password = null, $base = null)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->base = $base;
    }

    public static function create($host, $user = null, $password = null, $base = null): MySQLConnection
    {
        return new self($host, $user, $password, $base);
    }

    public function open(): MySQLConnection
    {
        $this->connection = mysqli_connect($this->host, $this->user, $this->password);
        if ($this->connection) {
            if ($this->base) {
                mysqli_selectdb($this->base, $this->connection);
            }
            $this->opened = true;
        } else {
            $this->opened = false;
            throw new DBException(mysqli_error(), mysqli_errno());
        }
        return $this;
    }

    public function close(): MySQLConnection
    {
        mysqli_close($this->connection);
        return $this;
    }

    public function opened(): bool
    {
        return $this->opened;
    }

}
