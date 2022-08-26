<?php

namespace analib\Util;

/**
 * Description of Session
 *
 * @author acround
 */
class Session extends StaticFactory
{

    const VAR_USER            = 'user';
    const VAR_CONTROLLER      = 'controller';
    const VAR_CONTROLLER_NAME = 'controllerName';
    const VAR_ACTION          = 'action';
    const VAR_ACTION_NAME     = 'actionName';
    const VAR_VERIFY_ERROR    = 'verify_error';

    static protected $status = false;

    static protected function getStatus()
    {
        return self::$status;
    }

    public static function init()
    {
        if (!self::getStatus()) {
            self::$status = true;
            return @session_start();
        }
    }

    public static function close()
    {
        if (!self::getStatus()) {
            return true;
        } else {
            session_commit();
            return true;
        }
    }

    public static function sessionId()
    {
        if (self::getStatus()) {

        } else {
            self::init();
        }
        return session_id();
    }

    public static function get($variable)
    {
        if (!self::getStatus()) {
            self::init();
        }
        if (isset($_SESSION[$variable])) {
            return $_SESSION[$variable];
        } else {
            return null;
        }
    }

    public static function getOnce($variable)
    {
        if (!self::getStatus()) {
            self::init();
        }
        if (isset($_SESSION[$variable])) {
            $ret = $_SESSION[$variable];
            unset($_SESSION[$variable]);
            return $ret;
        } else {
            return null;
        }
    }

    public static function getAll()
    {
        if (!self::getStatus()) {
            self::init();
        }
        return $_SESSION;
    }

    public static function has($variable)
    {
        if (!self::getStatus()) {
            self::init();
        }
        return isset($_SESSION[$variable]);
    }

    public static function set($variable, $value = null)
    {
        if (!self::getStatus()) {
            self::init();
        }
        $_SESSION[$variable] = $value;
        return true;
    }

    public static function clear($variable)
    {
        if (!self::getStatus()) {
            self::init();
        }
        $_SESSION[$variable] = null;
        return true;
    }

    public static function drop($variable)
    {
        if (!self::getStatus()) {
            self::init();
        }
        if (isset($_SESSION[$variable])) {
            unset($_SESSION[$variable]);
            return true;
        } else {
            return false;
        }
    }

}
