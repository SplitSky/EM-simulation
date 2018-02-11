<?php
// expects a json file
require_once 'config.php';

 header("Content-Type: application/json; charset=UTF-8");

 //capture the input
 $request = file_get_contents('php://input');

 //decode the JSON so it is useable in PHP
 $JSONobj = json_decode($request);

$username = $JSONobj->username;
$fileName = $JSONobj->fileName;
$fileContent = $JSONobj->content;

$result = file_put_contents($fileName ,$fileContent, FILE_APPEND);

// Create connection
$conn = new mysqli(dbServer, dbUser, dbPass, dbDatabase);
// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// run the query after validation for saving files
if ($result === FALSE) {
    echo json_encode("error, file can't be saved");
} else {
    $sql = 'INSERT INTO saved_simulation VALUES ("'. $fileName .'","' . $username .'");';
    $result = $conn->query($sql);   
}

if ($result === TRUE) {
    echo json_encode("success");
} else {
    echo json_encode("Error: " . $sql . "<br>" . $conn->error);
}

$conn->close();
?>
