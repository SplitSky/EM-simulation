<?php
// expects a json file
require_once 'config.php';
header("Content-Type: application/json; charset=UTF-8");
//capture the input
$request = file_get_contents('php://input');
//decode the JSON so it is useable in PHP
$JSONobj = json_decode($request);
$fileName = $JSONobj->filename;
// assigns the file name after the query
$content = file_get_contents($fileName);
// gets the content of the file
echo json_encode($content);
// sends the file content back for decoding
?>
