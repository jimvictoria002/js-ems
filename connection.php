<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ems2";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// $query = "SELECT * FROM scheduling_system.parent WHERE parent_id= 'vicky123' OR email= 'vicky123'";
// $result = $conn->query($query);
// $row = $result->fetch_assoc();

// print_r($row);


// $stmt = $conn->prepare("INSERT INTO `users` 
//                         (`firstname`, `middlename`, `lastname`, `email`, `access`, `is_verify`, `username`, `password`) 
//                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

// // Bind parameters
// $stmt->bind_param("ssssssss", $firstname, $middlename, $lastname, $email, $acess, $is_verify, $username, $password);

// $firstname = "Timothy";
// $middlename = "";
// $lastname = "Quimpan";
// $email = "tim123@gmail.com";
// $acess = "staff";
// $is_verify = "yes";
// $username = "tim123";
// $password = password_hash("tim12345678", PASSWORD_DEFAULT);

// if ($stmt->execute()) {
//     echo "New record inserted successfully";
// } else {
//     echo "Error: " . $stmt->error;
// }