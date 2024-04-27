<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ems2";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// $query = "SELECT * FROM sis.students";
// $result = $conn->query($query);

// while ($student = $result->fetch_assoc()) {
//     $firstname = $student['firstname'];
//     $lastname = $student['lastname'];
//     $middlename = isset($student['middlename']) ?  $student['middlename'] : '';
//     $student_fullname = $firstname . ' ' . $middlename . ' ' . $lastname;
//     $email = str_replace(' ', '', strtolower($firstname . $lastname . '@gmail.com'));
//     $std_id = $student['std_id'];

//     // $query = "UPDATE sis.students SET email = '$email' WHERE std_id = $std_id";
//     // $conn->query($query);

//     // $std_password = password_hash($firstname . '123', PASSWORD_DEFAULT);

//     // $query = "INSERT INTO sis.student_account (username, password) VALUES ($std_id, '$std_password')";
//     // $conn->query($query);

//     $parent_name = ' Parent ' . $middlename . ' ' .  $lastname;
//     $parent_password = password_hash('parent123', PASSWORD_DEFAULT);


//     $query = "INSERT INTO 
//                 sis.`parent`
//                 (`fullname`, `email`, `parent_id`, `password`, `contact_no`, `std_id`, `status`) 
//             VALUES 
//                 ('$parent_name','parent$email','" . str_replace(' ', '', strtolower($lastname)) . "123','$parent_password','1231241232132','$std_id','enable')";
//     $conn->query($query);
// }




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