<?php

$users = ['admin', 'student', 'teacher', 'parent', 'guest', 'staff'];
$access = isset($_GET['access']) ? $_GET['access'] : 'admin';
if (!in_array($access, $users)) {
    header('Location:index.php');
    exit;
}
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link href="../src/output.css" rel="stylesheet">
    <link href="../src/addition.css" rel="stylesheet">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="../node_modules/jquery-validation/dist/additional-methods.min.js"></script>
    <link rel="stylesheet" href="../node_modules/@fortawesome/fontawesome-free/css/all.css">
    <script src="../src/addition.js"></script>
    <link rel="shortcut icon" href="../ems-logo.png" type="image/x-icon">

    <style>
        .bn5 {
            padding: 0.6em 2em;
            border: none;
            outline: none;
            color: rgb(255, 255, 255);
            cursor: pointer;
            position: relative;
            z-index: 0;

        }

        .bn5:before {
            content: "";
            background: linear-gradient(45deg,
                    #ff0000,
                    #ff7300,
                    #fffb00,
                    #48ff00,
                    #00ffd5,
                    #002bff,
                    #7a00ff,
                    #ff00c8,
                    #ff0000);
            position: absolute;
            top: -2px;
            left: -2px;
            background-size: 400%;
            z-index: -1;
            filter: blur(5px);
            width: calc(100% + 4px);
            height: calc(100% + 4px);
            animation: glowingbn5 20s linear infinite;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        @keyframes glowingbn5 {
            0% {
                background-position: 0 0;
            }

            50% {
                background-position: 400% 0;
            }

            100% {
                background-position: 0 0;
            }
        }

        .bn5:active {
            color: #000;
        }

        .bn5:active:after {
            background: transparent;
        }

        .bn5:hover:before {
            opacity: 1;
        }

        .bn5.teacher:hover:after {
            background: #218838;
        }

        .bn5.parent:hover:after {
            background: #17a2b8;
        }

        .bn5.student:hover:after {
            background: #dc3545;
        }

        .bn5.guest:hover:after {
            background: royalblue;
        }

        .bn5:after {
            z-index: -1;
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;

            transition: all ease .4s;
        }
    </style>
</head>

<body class="flex justify-center items-center h-screen bg-green-50">

    <div class="flex items-center w-[50%] gap-2">
        <div class="flex flex-col w-full">
            <div class="flex items-center justify-center md:flex-row flex-col w-[100%] lg:py-0 my-[10rem] ">
                <div class="flex justify-center w-full md:w-[70%] min-w-[25rem] rounded-lg  overflow-hidden shadow-lg">

                    <form action="../backend/auth/send_token.php" method="post" class="w-full shadow-md px-6 flex flex-col py-16 justify-center items-center border rounded-lg bg-white" id="login">


                        <?php if (isset($_SESSION['email_sent'])) { ?>
                            <p class="text-white py-3 bg-green-400 rounded-md font-semibold px-2 w-full mb-5">
                                <?= $_SESSION['email_sent'] ?>
                            </p>
                        <?php unset($_SESSION['email_sent']);
                        } ?>
                        <div class="flex flex-col w-full mb-7">
                            <label for="email" class=" mb-2 font-semibold text-sm">Email</label>
                            <div class="relative">
                                <input type="text" name="email" id="email" class="w-full  px-2 py-2 border rounded-md focus:outline-none " placeholder="Enter email">

                            </div>
                        </div>
                        <button type="submit" class=" bg-main hover:bg-green-800 text-white transition-all ease duration-300 w-36 text-base text-s-bg rounded-sm py-1 self-center text-center mt-5" id="login-btn">Request</button>
                        <div class="flex items-start flex-col w-full mt-5">
                            <a href="index.php" class="hover:underline hover:text-s-head text-sm mt-7">Back to homepage</a>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                $('#login').validate({

                    rules: {
                        email: {
                            required: true,
                            email: true,
                            remote: {
                                url: "../backend/validator/verify_email.php",
                                type: "post",
                                data: {
                                    email: function() {
                                        return $("#email").val();
                                    }
                                }
                            }
                        }

                    },
                    errorPlacement: function(error, element) {
                        $(element).parent().append(error.addClass('text-red-700 text-sm font-semibold'))
                        $(element).addClass('border-red-600')
                    },
                    success: function(label, element) {
                        $(element).removeClass('border-red-600')
                    },
                    messages: {
                        email: {
                            required: "Please enter the email",
                            email: "Please enter a valid email address",
                            remote: "This email is not registerd"
                        },
                        password: 'Password is required'
                    },
                });


                function showPassword() {
                    var passwordField = document.getElementById("passwordField");
                    var eyeIcon = document.getElementById("eye");

                    if (passwordField.type === "password") {
                        passwordField.type = "text";
                    } else {
                        passwordField.type = "password";
                    }
                }
            </script>
        </div>
    </div>


</body>


</html>