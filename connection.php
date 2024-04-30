<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ems2";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}





// function insertResponse($respondent, $event_id, $limit)
// {

//     switch ($respondent) {
//         case 'teacher':
//             $query = "SELECT id as response_id FROM scheduling_system.teacher LIMIT $limit";
//             break;
//         case 'student':
//             $query = "SELECT std_id as response_id FROM sis.students LIMIT $limit";
//             break;
//         case 'parent':
//             $query = "SELECT id as response_id FROM sis.parent LIMIT $limit";
//             break;

//         default:
//             return "Invalid respondent";
//             break;
//     }

//     global $conn;


//     $result = $conn->query($query);

//     while ($res = $result->fetch_assoc()) {
//         $response_id = $res['response_id'];
//         $query = "INSERT INTO 
//                     response_form (event_id, respondent, response_id, is_done)
//                   VALUES
//                     ($event_id, '$respondent', $response_id, 'yes')";
//         $conn->query($query);

//         $r_f_id = $conn->insert_id;

//         $query = "SELECT f_id FROM events e WHERE e.event_id = $event_id";
//         $re_f_id = $conn->query($query);
//         $f_id = $re_f_id->fetch_assoc()['f_id'];

//         $q_questionnaire = "SELECT * FROM questionnaire WHERE f_id = $f_id";
//         $r_questionnaire = $conn->query($q_questionnaire);

//         while ($questionnaire = $r_questionnaire->fetch_assoc()) {
//             $q_id = $questionnaire['q_id'];
//             $type = $questionnaire['type'];
//             $required = $questionnaire['required'];

//             // if ($required == 'yes') {
//                 if ($type == 'radio') {
//                     $choice_arr = [];

//                     $q_choices = "SELECT * FROM choices WHERE q_id = $q_id";
//                     $r_choices = $conn->query($q_choices);

//                     while ($choice = $r_choices->fetch_assoc()) {
//                         $choice_arr[] = $choice['c_id'];
//                     }

//                     $randomKey = array_rand($choice_arr);

//                     $answer = $choice_arr[$randomKey];
//                 } else {
//                     $choice_arr = [
//                         'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
//                         'Consequuntur magni quia voluptatibus reprehenderit aut quod molestiae!',
//                         'Nisi adipisci placeat accusantium laboriosam, officiis delectus eos odio repellat blanditiis necessitatibus official'
//                     ];

//                     $randomKey = array_rand($choice_arr);

//                     $answer = $choice_arr[$randomKey];
//                 }
//                 $query = "INSERT INTO 
//                         response (r_f_id, q_id, answer)
//                       VALUES ($r_f_id, $q_id, '$answer')";
//                 $conn->query($query);
//             // }
//         }
//     }
// }


// insertResponse('teacher', 170, 24);
















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

//     $parent_password = password_hash('parent123', PASSWORD_DEFAULT);


//     $query = "INSERT INTO 
//                 sis.`parent`
//                 (`firstname`,`middlename`, `lastname`,  `email`, `parent_id`, `password`, `contact_no`, `std_id`, `status`) 
//             VALUES 
//                 ('Parent','$middlename','$lastname','parent$email','" . str_replace(' ', '', strtolower($lastname)) . "$std_id','$parent_password','1231241232132','$std_id','enable')";
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