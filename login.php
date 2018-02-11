<?php
require_once 'config.php';
 header("Content-Type: application/json; charset=UTF-8");

$userID = $_POST["username"];
$password = $_POST["password"];

//print_r("username: " . $userID );
//print_r("password: " . $password );
// Create connection
$conn = new mysqli(dbServer, dbUser, dbPass, dbDatabase);
// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT userID, password
        FROM userdata WHERE userID='" . $userID . "'";

$result = $conn->query($sql);

//print_r ("num rows: " . $result->num_rows);

 if ($result->num_rows == 1) {
     $row = $result->fetch_assoc();
     if ($row['password'] == $password) {
        echo json_encode("success");
     } else {
         echo json_encode("The password is incorrect");
     }
    
 } else {
        echo json_encode("The user doesn't exist");
 }

$conn->close();
?>