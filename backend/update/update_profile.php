<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();


    //File handling
    if ($_FILES['user_img']['name']) {
        echo "Has image </br>";
        $uploadDir = '../../uploads/user_img/';

        $fileName = basename($_FILES['user_img']['name']);

        $targetFile = $uploadDir . $fileName;

        $fileCount = 1;

        while (file_exists($targetFile)) {
            $fileInfo = pathinfo($fileName);
            $fileName = $fileInfo['filename'] . '_' . $fileCount . '.' . $fileInfo['extension'];
            $targetFile = $uploadDir . $fileName;
            $fileCount++;
        }

        if (!move_uploaded_file($_FILES["user_img"]["tmp_name"], $targetFile)) {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }




        $user_id = $_SESSION['user_id'];

        $query = "SELECT user_img FROM users WHERE user_id = $user_id";
        $result = $conn->query($query);

        $fileNameToDelete = $result->fetch_assoc()['user_img'];
        if ($fileNameToDelete != 'default-img.png') {
            if (file_exists($uploadDir . $fileNameToDelete)) {
                // Attempt to delete the file
                if (unlink($uploadDir . $fileNameToDelete)) {
                    echo "File deleted successfully.";
                } else {
                    echo "Error deleting file.";
                }
            }
        }




        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $_SESSION['password'];
        $user_id = $_SESSION['user_id'];
        $fileNameToStore = $fileName;



        $query = "UPDATE users SET 
            firstname = ?, 
            middlename = ?, 
            lastname = ?, 
            email = ?, 
            username = ?, 
            password = ? ,
            user_img = ?
            WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $firstname, $middlename, $lastname, $email, $username, $password, $fileNameToStore, $user_id);

        if ($stmt->execute()) {
            $query = "SELECT * FROM users WHERE user_id = $user_id";
            $result = $conn->query($query);
            $user = $result->fetch_assoc();

            foreach ($user as $key => $value) {
                $_SESSION[$key] = $value;
            }

            $referrer = $_SERVER['HTTP_REFERER'];
            $_SESSION['success'] = 'Profile updated successfuly';
            header("Location: $referrer");
        } else {
            $referrer = $_SERVER['HTTP_REFERER'];
            $_SESSION['failed'] = "Can't update profile";
            header("Location: $referrer");
        }
    } else {



        //Insertion
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $_SESSION['password'];
        $user_id = $_SESSION['user_id'];



        $query = "UPDATE users SET 
            firstname = ?, 
            middlename = ?, 
            lastname = ?, 
            email = ?, 
            username = ?, 
            password = ? 
            WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $firstname, $middlename, $lastname, $email, $username, $password, $user_id);

        if ($stmt->execute()) {
            $query = "SELECT * FROM users WHERE user_id = $user_id";
            $result = $conn->query($query);
            $user = $result->fetch_assoc();

            foreach ($user as $key => $value) {
                $_SESSION[$key] = $value;
            }

            $referrer = $_SERVER['HTTP_REFERER'];
            $_SESSION['success'] = 'Profile updated successfuly';
            header("Location: $referrer");
        } else {
            $referrer = $_SERVER['HTTP_REFERER'];
            $_SESSION['failed'] = "Can't update profile";
            header("Location: $referrer");;
        }
    }
}
