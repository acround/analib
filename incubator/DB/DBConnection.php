<?php

namespace analib\incubator\DB;

/**
 * Description of DBConnection
 *
 * @author acround
 */
abstract class DBConnection
{

    protected $host;
    protected $user;
    protected $password;
    protected $base;
    protected $connection;

    abstract public function __construct($host, $user = null, $password = null, $base = null);

    abstract public function open();

    abstract public function close();

    abstract public function opened();

    public function getHost()
    {
        return $this->host;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getConnection()
    {
        return $this->connection;
    }

}
