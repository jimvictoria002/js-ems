<?php
session_start();



?>


<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../src/output.css" rel="stylesheet">
    <link href="../src/addition.css" rel="stylesheet">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="../node_modules/jquery-validation/dist/additional-methods.min.js"></script>
    <link rel="stylesheet" href="../node_modules/@fortawesome/fontawesome-free/css/all.css">
    <script src="../src/addition.js"></script>
    <link rel="shortcut icon" href="../ems-logo.png" type="image/x-icon">
    <title>
        Login
    </title>

    <style>
        label.error {
            color: rgb(150, 31, 31);
            font-size: 14px;
        }
    </style>
</head>

<body class=" flex justify-center items-center ">
    <div class="bg-main opacity-80  w-screen fixed inset-0 z-[-9999]">

    </div>
    <div
        class="flex flex-col mx-auto gap-y-10  my-[5rem] md:my-[10rem] md:flex-row w-[90%] md:w-[90%] lg:w-[70%]  rounded-2xl overflow-hidden ">

        <div class="flex flex-col w-full justify-center bg-main px-2 py-10 rounded-3xl md:rounded-none">
            <h1 class="text-white font-semibold text-xl md:text-3xl pl-5">Welcome to</h1>
            <div class=" relative  w-full ">
                <img src="../ems-logo.png" alt="logo" class=" block m-auto opacity-80 p-10">
            </div>
            <h1 class="text-white font-semibold text-xl md:text-3xl text-center ">Event Management System</h1>

        </div>
        <div class="w-full   rounded-3xl md:rounded-none  shadow-md px-6 flex flex-col py-16 justify-center items-center border bg-white"
            >

            <div class="flex justify-center flex-col w-full items-center pb-4">
                <div class="flex w-28 md:w-32 items-center gap-2">
                    <img src="../ems-logo.png" alt="logo">
                </div>
            </div>
            <?php if (isset($_SESSION['change_success'])) { ?>
                <p class="text-white py-3 bg-green-400 rounded-md font-semibold px-2 w-full">
                    <?= $_SESSION['change_success'] ?>
                </p>
                <?php unset($_SESSION['change_success']);
            } ?>
            <div class="flex mb-3 py-2 lg:py-3 self-start">
                <p class="text-xl md:text-2xl text-s-head font-semibold">Login</p>
            </div>
            <div class="flex mb-3 capitalize self-end">
                <select name="access" onchange="changeForm(this)" id="access" class="border p-1  w-32 rounded-sm">
                    <option value="admin">Access</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                    <option value="guest">Guest</option>
                </select>
            </div>


            <div class="w-full" id="login-form">

                <?php

                require "./components/inputs-form/main-user.php";

                ?>
            </div>


        </div>

        <script>
            function changeForm(e) {

                let access = $(e).val();

                if (access != '') {
                    $.ajax({
                        type: "GET",
                        url: "./components/change-login-form.php",
                        data: {
                            access: access
                        },
                        success: function (response) {

                            $('#login-form').html(response);
                        }
                    });
                }



            }
        </script>
    </div>
</body>

</html>