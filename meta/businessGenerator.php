<?php

define('META', 'meta');
define('ANALIB_DIR_ROOT', realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR);
define('ANALIB_DIR_LIB', ANALIB_DIR_ROOT);
include(ANALIB_DIR_ROOT . 'config.inc.php');
$pathBisinessOut = ANALIB_DIR_LIB . ANALIB_DIR_BUSINESS_NAME;
$pathProtoOut    = ANALIB_DIR_LIB . ANALIB_DIR_PROTO_NAME;
$meta            = __DIR__ . DIRECTORY_SEPARATOR . \analib\Core\Build\Business\BusinessBuilder::DEFAULT_META_NAME;
$pathBuilder     = ANALIB_DIR_LIB . DIRECTORY_SEPARATOR . META . DIRECTORY_SEPARATOR . 'businessBuilder.php';
$_SERVER['argv'] = array(
    '',
    $pathBisinessOut,
    $pathProtoOut,
    $meta
);
include $pathBuilder;
