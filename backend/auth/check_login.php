<?php

use PhpOffice\PhpSpreadsheet\Shared\PasswordHasher;

include('../../connection.php');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();




    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $access = $_POST['access'];

    if ($access == 'parent') {
        $stmt = $conn->prepare("SELECT * FROM sis.parent WHERE parent_id=? OR email=?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
    } else if ($access == 'teacher') {
        $stmt = $conn->prepare("SELECT e.* FROM hrms.employees e LEFT JOIN hrms.position p ON e.position_id = p.id LEFT JOIN departments d ON p.dept_id = d.dept_id WHERE e.email=? AND d.dept_id = 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    } else if ($access == 'admin') {
        $stmt = $conn->prepare("SELECT * FROM users WHERE (username=? OR email=?) AND access = ?");
        $stmt->bind_param("sss", $username, $username, $access);
        $stmt->execute();
        $result = $stmt->get_result();
    } else if ($access == 'guest') {


        $_SESSION = $_POST;


        $query = "SELECT response_id FROM response_form WHERE respondent = 'guest' ORDER BY response_id DESC LIMIT 1";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $guest_id = (isset($row['response_id']) ? $row['response_id']  : 0);
        $_SESSION['user_id'] = $guest_id + 1;
        // print_r($_SESSION);

        echo "correct";

        return;
        exit;
    } else if ($access == 'student') {

        // $stmt = $conn->prepare("SELECT 
        //                             sa.student_id as user_id,
        //                             sa.student_password as password,
        //                             s.first_name as firstname,
        //                             s.middle_name as middlename,
        //                             s.last_name as lastname,
        //                             s.email
        //                         FROM schooldb.students_account_info sa INNER JOIN schooldb.students_personal_info s  ON sa.student_id = s.student_id WHERE s.student_id = ?");
        // $stmt->bind_param("s", $username);
        // $stmt->execute();
        // $result = $stmt->get_result();

        $stmt = $conn->prepare("SELECT * FROM sis.student_account sa INNER JOIN sis.student_data s  ON sa.username = s.std_id WHERE s.std_id = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    } else if ($access == 'staff') {
        $stmt = $conn->prepare("SELECT * FROM users WHERE (username=? OR email=?) AND access = ?");
        $stmt->bind_param("sss", $username, $username, $access);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        echo 'invalid';
    }



    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // print_r($row);

        //Guest credentials
        if ($access == 'guest') {
            foreach ($row as $key => $value) {
                if ($key == 'id' || $key == 'guest_id') {
                    $key = 'user_id';
                }
                $_SESSION[$key] = $value;
            }
            $_SESSION['access'] = $access;
            echo "correct";
        } else {
            //User credentials
            $hashedPassword = $row['password'];

            if (password_verify($password, $hashedPassword)) {
                foreach ($row as $key => $value) {
                    if ($access == 'teacher' || $access == 'parent') {
                        if ($key == 'id') {
                            $key = 'user_id';
                        }
                    }
                    if ($access == 'student') {
                        if ($key == 'std_id') {
                            $key = 'user_id';
                        }
                    }
                    $_SESSION[$key] = $value;
                }
                $_SESSION['access'] = $access;
                echo "correct";
            } else {
                echo 'invalid';
            }
        }
    } else {
        echo 'invalid';
    }
}
