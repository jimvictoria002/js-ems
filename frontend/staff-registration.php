<?php
require "../connection.php";
if (!isset($_GET['verification_token'])) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

$verification_token = $_GET['verification_token'];

$query = "SELECT * FROM verification_token WHERE token = '$verification_token'";
$result = $conn->query($query);


if ($result->num_rows < 1) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

$email = $result->fetch_assoc()['email'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="../src/output.css" rel="stylesheet">
    <link href="../src/addition.css" rel="stylesheet">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="../node_modules/jquery-validation/dist/additional-methods.min.js"></script>
    <link rel="stylesheet" href="../node_modules/@fortawesome/fontawesome-free/css/all.css">
    <script src="../src/addition.js"></script>
    <link rel="shortcut icon" href="../ems-logo.png" type="image/x-icon">

</head>

<body class="flex justify-center items-center h-screen bg-green-50">

    <div class="flex items-center w-[80%] gap-2">
        <p>Hello <?= $email  ?></p>
    </div>


</body>


</html>