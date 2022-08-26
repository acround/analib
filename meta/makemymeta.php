<?php

use analib\Core\Build\MySQL\MyMetaMaker;

$db        = isset($argv[1]) ? $argv[1] : 'information_schema';
//if (!file_exists($db)) {
//    echo $db . " - File not exists\n";
//    exit;
//}
include '../Core/Build/MySQL/MyMetaMaker.php';
include '../config.inc.php';
$metaMaker = MyMetaMaker::create('localhost', 'acround', '12cool09', $db);
$xml       = $metaMaker->makeXml();
file_put_contents($db . '.xml', $xml->saveXML());

//$metaMaker = MyMetaMaker::create('localhost', 'acround', '12cool09', 'cake01');
//$xml = $metaMaker->makeXml();
//file_put_contents('cake.xml', $xml->saveXML());
