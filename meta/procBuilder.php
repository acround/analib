<?php

//==============================================================================
//
//==============================================================================
set_include_path(
    get_include_path() . PATH_SEPARATOR .
    realpath(dirname(__FILE__)) . PATH_SEPARATOR .
    realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'classes') . PATH_SEPARATOR
);
require_once '../Core/Build/Ora/OraPackageBuilder.php';
require_once '../Core/Build/Ora/OraProcedureBuilder.php';
$pathOut  = '';
$pathMeta = '';
$args     = $_SERVER['argv'];
if (isset($args[1])) {
    $pathMeta = $args[1];
}
if (isset($args[2])) {
    $pathOut = $args[2];
}

if (!$pathMeta) {
    $pathMeta = dirname(__FILE__) . DIRECTORY_SEPARATOR . \analib\Core\Build\Ora\OraModelBuilder::DEFAULT_META_NAME;
}
if (!$pathOut) {
    $pathOut = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'model');
}
$p   = realpath($pathOut . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . ANALIB_DIR_CLASS_NAME . DIRECTORY_SEPARATOR . 'OraExtPackage' . ANALIB_EXT_CLASS);
$ext = file_exists($p);
\analib\Core\Build\Ora\OraModelBuilder::create(
        $pathMeta, $pathOut
    )->
    setExt($ext)->
    build()->
    dump();
//==============================================================================
