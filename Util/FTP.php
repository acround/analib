<?php

namespace analib\Util;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class FTP
{

    private $server   = null;
    private $path     = null;
    private $login    = null;
    private $password = null;
    private $ftp      = null;

    public function __construct($server, $path = null, $login = null, $password = null)
    {
        $this->server   = $server;
        $this->path     = $path;
        $this->login    = $login;
        $this->password = $password;
    }

    /**
     *
     * @param string $server
     * @param string $path
     * @param string $login
     * @param string $password
     * @return \FTP
     */
    public static function create($server, $path = null, $login = null, $password = null)
    {
        return new self($server, $path, $login, $password);
    }

    /**
     *
     * @param string $server
     * @return \FTP
     */
    public function setServer($server)
    {
        $this->server = $server;
        return $this;
    }

    /**
     *
     * @param string $path
     * @return \FTP
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     *
     * @param string $login
     * @return \FTP
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     *
     * @param string $password
     * @return \FTP
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     * @return \FTP
     * @throws FTPException
     */
    public function connect()
    {
        $this->ftp = ftp_connect($this->getServer());
        if (!$this->ftp) {
            throw new \analib\Core\Exceptions\FTPException('Not connect to host:' . $this->getServer(), 1);
        }
        return $this;
    }

    /**
     *
     * @param string $login
     * @param string $password
     * @return \FTP
     * @throws FTPException
     */
    public function login($login = null, $password = null)
    {
        if ($login) {
            $this->setLogin($login);
        }
        if ($password) {
            $this->setPassword($password);
        }

        if (!ftp_login($this->ftp, $login, $password)) {
            throw new \analib\Core\Exceptions\FTPException('Not logged to host:' . $this->getServer(), 2);
        }
        return $this;
    }

    /**
     *
     * @param string $path
     * @return \FTP
     * @throws FTPException
     */
    public function chdir($path = null)
    {
        if ($path) {
            $this->setPath($path);
        }

        if (!ftp_chdir($this->ftp, $path)) {
            throw new \analib\Core\Exceptions\FTPException('Not change directory to:' . $this->getPath(), 3);
        }
        return $this;
    }

    /**
     *
     * @return \FTP
     */
    public function open()
    {
        $this->connect();
        if ($this->getLogin()) {
            $this->login();
        }
        if ($this->getPath()) {
            $this->chdir();
        }
        return $this;
    }

    /**
     *
     * @return \FTP
     */
    public function close()
    {
        ftp_close($this->ftp);
        $this->ftp = null;
        return $this;
    }

}
