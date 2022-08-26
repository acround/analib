<?php

date_default_timezone_set('Europe/Moscow');

if (!defined('ANALIB_DIR_LIB')) {
    define('ANALIB_DIR_LIB', __DIR__);
}

require_once ANALIB_DIR_LIB . DIRECTORY_SEPARATOR . 'constants.php';
require_once ANALIB_DIR_LIB_EXCEPTIONS . DIRECTORY_SEPARATOR . 'BaseException' . ANALIB_EXT_EXCEPTION;
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
