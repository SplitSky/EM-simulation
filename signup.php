<?php

require_once 'config.php';

 header("Content-Type: application/json; charset=UTF-8");

 //capture the input
 $request = file_get_contents('php://input');

 //decode the JSON so it is useable in PHP
 $JSONobj = json_decode($request);



$userID = $_POST["username"];
$password = $_POST["password"];


// Create connection
$conn = new mysqli(dbServer, dbUser, dbPass, dbDatabase);
// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// password hashing start

// password hashing end

$sql = "INSERT INTO userdata (userID, password) VALUES (?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $userID, $password);


if ($stmt->execute() === TRUE) {
    $_SESSION["userID"] = $userID;
    
    echo json_encode("success");
} else {
    echo json_encode("Error: " . $sql . "<br>" . $conn->error);
}

$conn->close();
?>
