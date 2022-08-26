<?php

//==============================================================================
//
//==============================================================================
$debug           = false;
set_include_path(
    get_include_path() . PATH_SEPARATOR .
    realpath(dirname(__FILE__)) . PATH_SEPARATOR .
    realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'classes') . PATH_SEPARATOR
);
require_once '../config.inc.php';
$pathBisinessOut = '';
$pathProtoOut    = '';
$meta            = '';
$args            = $_SERVER['argv'];
if (isset($args[1])) {
    $meta = $args[1];
}

if (isset($args[2])) {
    $pathBisinessOut = $args[2];
}
if (isset($args[3])) {
    $pathProtoOut = $args[3];
}

if (isset($args[4])) {
    $debug = $args[4];
}
if ($debug) {
    define('DEBUG', true);
} else {
    define('DEBUG', false);
}

if (!$meta) {
    $meta = dirname(__FILE__) . DIRECTORY_SEPARATOR . \analib\Core\Build\Business\BusinessBuilder::DEFAULT_META_NAME;
}
if (!$pathBisinessOut) {
    $pathBisinessOut = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes';
}
if (!file_exists($pathBisinessOut)) {
    mkdir($pathBisinessOut);
}
if (!$pathProtoOut) {
    $pathProtoOut = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'proto';
}
if (!file_exists($pathProtoOut)) {
    mkdir($pathProtoOut);
}
\analib\Core\Build\Business\BusinessBuilder::create(
        $pathBisinessOut, $pathProtoOut, $meta
    )->
    build()->
    dump();
//==============================================================================
