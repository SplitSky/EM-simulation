<?php
// expects a json file
require_once 'config.php';
header("Content-Type: application/json; charset=UTF-8");
//capture the input
$request = file_get_contents('php://input');
//decode the JSON so it is useable in PHP
$JSONobj = json_decode($request);
$fileName = $JSONobj->filename;
// deletes the file
unlink($fileName);
// removes the entry from the database
$conn = new mysqli(dbServer, dbUser, dbPass, dbDatabase);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "DELETE FROM saved_simulation WHERE filename='" . $fileName . "';";

if ($conn->query($sql) === TRUE) {
    echo json_encode("Record deleted successfully");
} else {
    echo "Error deleting record: " . $conn->error;
}
// sends the file content back for decoding
?>
