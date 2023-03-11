<?php

// ini_set('display_errors', 'on');
// error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With');

include('db.class.php');

$db = new Db();
$result = $db->addTask($_REQUEST['task']);

echo json_encode($result);