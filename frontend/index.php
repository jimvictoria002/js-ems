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

    <div class="flex items-center w-[80%] gap-2">
        <div class="w-[60%] hidden md:flex justify-center opacity-90">
            <img src="../ems-logo.png" alt="ems-logo" class="w-[70%]">
        </div>
        <div class="flex flex-col w-full">
            <div class="welcome-container flex flex-col gap-y-2">
                <p class="text-lg md:text-xl lg:text-2xl md:text-start text-center">Welcome to</p>
                <p class="text-5xl lg:text-6xl font-sans font-semibold md:text-start text-center text-green">Event Management System</p>
                <div class="text-base font-semibold mt-5 md:text-start text-center">Login as...</div>
            </div>
            <div class="btns-container mt-2 w-full flex gap-5 flex-wrap md:justify-start justify-center">
                <a href="./login.php?access=teacher" class="md:w-40 text-center w-32 text-sm md:text-base shadow-md rounded-sm teacher py-1.5  font-semibold bn5 bg-[#218838]">Teacher</a>
                <a href="./login.php?access=parent" class="md:w-40 text-center w-32 text-sm md:text-base shadow-md rounded-sm parent py-1.5  font-semibold bn5 bg-[#17a2b8]">Parent</a>
                <a href="./login.php?access=student" class="md:w-40 text-center w-32 text-sm md:text-base shadow-md rounded-sm student py-1.5  font-semibold bn5 bg-[#dc3545]">Student</a>
                <a href="./login.php?access=guest" class="md:w-40 text-center w-32 text-sm md:text-base shadow-md rounded-sm guest py-1.5  font-semibold bn5 bg-[royalblue]">Guest</a>
            </div>
        </div>
    </div>

</body>


</html>