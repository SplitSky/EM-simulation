<?php

require_once 'config.php';

header("Content-Type: application/json; charset=UTF-8");

$request = file_get_contents('php://input');

//decode the JSON so it is useable in PHP
$JSONobj = json_decode($request);

$username = $JSONobj->username;

// Create connection
$conn = new mysqli(dbServer, dbUser, dbPass, dbDatabase);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} // connection check

$sql = 'SELECT filename FROM saved_simulation WHERE userID = "' . $username . '";';
$result = $conn->query($sql);

    $fileNames = array();
    $fileNames = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($fileNames);

?>