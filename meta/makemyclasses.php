<?php

$fileName = isset($argv[1]) ? $argv[1] : 'mymeta.xml';
if (!file_exists($fileName)) {
    echo $fileName . " - File not exists\n";
    exit;
}
include '../config.inc.php';
include '../Core/Build/MySQL/MyModelBuilder.php';
include '../Core/Build/MySQL/MyObjectBuilder.php';
include '../Core/Build/MySQL/MyObjectPropertyBuilder.php';
$params  = [
    'meta'     => $fileName,
    'classes'  => '../classes',
    'do_proto' => false,
    'fields'   => analib\Core\Build\MySQL\MyModelBuilder::FIELDS_TYPE_FIELD,
    'setget'   => false,
    'rewrite'  => true,
    'suffix'   => null,
];
$builder = \analib\Core\Build\MySQL\MyModelBuilder::create($params);
$builder->build()->dump();
