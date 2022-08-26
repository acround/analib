<?php

require_once ANALIB_DIR_LIB . DIRECTORY_SEPARATOR . 'constants.php';
require_once ANALIB_DIR_LIB_EXCEPTIONS . DIRECTORY_SEPARATOR . 'BaseException' . ANALIB_EXT_EXCEPTION;
require_once ANALIB_DIR_LIB_EXCEPTIONS . DIRECTORY_SEPARATOR . 'NotFoundException' . ANALIB_EXT_EXCEPTION;
require_once ANALIB_DIR_LIB_EXCEPTIONS . DIRECTORY_SEPARATOR . 'ClassNotFoundException' . ANALIB_EXT_EXCEPTION;

function error2Exception($code, $string, $file, $line, $context)
{
    throw new \analib\Core\Exceptions\BaseException($string, $code, 1, $file, $line);
}

function analibAutoload($className)
{
    $namePath = explode('\\', $className);
    $topDir   = realpath(ANALIB_DIR_LIB . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
    $filePath = $topDir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $namePath) . ANALIB_EXT_CLASS;
    if (file_exists($filePath)) {
        include $filePath;
        return true;
    } else {
//        print_r($namePath);
        echo "\n";
//        print_r($filePath);
        echo "\n";
        return false;
    }
}

spl_autoload_register('analibAutoload');
set_error_handler('error2Exception', E_ALL | E_STRICT);
