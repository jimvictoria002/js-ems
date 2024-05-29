<?php
require "../connection.php";
if (!isset($_GET['token'])) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

$verification_token = $_GET['token'];

$query = "SELECT * FROM password_reset_token WHERE token = '$verification_token'";
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


    <div class="w-full px-3 fixed inset-0 z-40 " id="create-staff-modal">
        <div class=" bg-white rounded-lg z-50 px-5 w-[20rem] md:w-[25rem] max-h-[90vh] overflow-auto py-5 pb-0 fixed top-[50%] left-[50%] flex flex-col gap-2" style="transform: translate(-50%, -50%);" id="invitation-form">

            <div class="flex justify-between items-start flex-col">
                <p class="text-xl md:text-2xl font-semibold mb-2 md:mb-3 text-green-950">Password reset</p>
    <p class="text-sm text-semibold mb-6">For <?= $email ?></p>
            </div>


            <!-- Create staff -->

            <form action="../backend/auth/password_reset.php" method="POST" class="flex flex-col gap-y-3" id="create-form">
                

                <div class="flex flex-col ">
                    <div class="flex justify-between items-end">

                        <label for="username" class="font-semibold capitalize">password<span class="text-red-700">*</span></label>
                        <label for="confirm password" class="text-xs text-main font-semibold pr-2">Must be 8 characters long</label>
                    </div>
                    <input type="password" placeholder="Enter password" class="p-1 border active:border-green-950 passwordField rounded-sm w-full" name="password" id="password">
                </div>

                <div class="flex flex-col ">
                    <div class="flex justify-between items-end">

                        <label for="username" class="font-semibold capitalize">Confirm password<span class="text-red-700">*</span></label>
                    </div>
                    <input type="password" placeholder="Enter confirm password" class="p-1 border active:border-green-950 passwordField rounded-sm w-full" name="confirmPassword">
                </div>
                <div class="flex w-full ml-0.5">
                    <input type="checkbox" id="eye" onclick="showPassword()" class="mr-1 text-xs">
                    <label for="eye" class="text-sm">Show passwords</label>
                </div>
                <script>
                    function showPassword() {
                        var passwordFields = document.querySelectorAll('.passwordField');

                        passwordFields.forEach(function(field) {
                            if (field.type === "password") {
                                field.type = "text";
                            } else {
                                field.type = "password";
                            }
                        });
                    }
                </script>

                <input type="hidden" name="token" value="<?= $verification_token ?>">
                <input type="hidden" name="email" value="<?= $email ?>">

                <button class=" px-6 py-1.5 md:py-1 self-end md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-md mt-2 mr-2  mb-5" id="create-btn">Reset</button>
            </form>

            <script>
                $('#create-form').validate({
                    errorPlacement: function(error, element) {
                        error.addClass("text-red-700 text-sm font-semibold");
                        error.insertAfter(element);
                    },
                    rules: {
                        firstname: {
                            required: true
                        },
                        lastname: {
                            required: true
                        },
                        email: {
                            required: true,
                            email: true,
                            remote: {
                                url: "../backend/validator/check_email.php",
                                type: "post",
                                data: {
                                    email: function() {
                                        return $("#email2").val();
                                    }
                                }
                            }
                        },
                        username: {
                            required: true,
                            minlength: 5,
                            remote: {
                                url: "../backend/validator/check_username.php",
                                type: "post",
                                data: {
                                    username: function() {
                                        return $("#username").val();
                                    }
                                }
                            }
                        },
                        password: {
                            required: true,
                            minlength: 8
                        },
                        confirmPassword: {
                            required: true,
                            minlength: 8,
                            equalTo: "#password"
                        }
                    },
                    messages: {
                        email: {
                            email: "Please enter a valid email address",
                            remote: "This email is existing"
                        },
                        username: {
                            minlength: "Username must be at least 5 characters long",
                            remote: "This username is not available"
                        },
                        password: {
                            minlength: "Password must be at least 8 characters long"
                        },
                        confirmPassword: {
                            minlength: "Password must be at least 8 characters long",
                            equalTo: "Passwords do not match"
                        }
                    },
                    submitHandler: function(form) {

                        if ($('#create-form').validate()) {
                            $('#create-btn').prop('disabled', true);
                            $('#create-btn').css('opacity', '.30');
                            form.submit();
                        }
                    }
                });
            </script>

        </div>
        <div class="toggle-create-staff bg-gray-700 opacity-30 fixed inset-0 -z-50"></div>
    </div>

</body>


</html>