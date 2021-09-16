<?php

$config = dirname(__FILE__) . '/../../../videos/configuration.php';
require_once $config;
session_write_close();
header('Content-Type: application/json');

$obj = new stdClass();
$obj->error = true;
$obj->msg = "";

$isEnabled = AVideoPlugin::isEnabledByName('CDN');
if(!$isEnabled){
    $obj->msg = "CDN is disabled";
    die(json_encode($obj));
}

if(empty($_REQUEST['videos_id'])){
    $_REQUEST['videos_id'] = intval(@$argv[1]);
}

if (empty($_REQUEST['videos_id'])) {
    $obj->msg = "Video ID is empty";
    die(json_encode($obj));
}

if(!isCommandLineInterface()){
    if(!Video::canEdit($_REQUEST['videos_id'])){
        $obj->msg = "Command line only";
        die(json_encode($obj));
    }
}

$obj->error = false;
$obj->response = CDNStorage::moveRemoteToLocal($_REQUEST['videos_id']);  

echo json_encode($obj);
