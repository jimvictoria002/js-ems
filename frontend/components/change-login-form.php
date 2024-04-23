<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

   $access = $_GET['access'];

   if ($access == 'student') {
      require "./inputs-form/student-login.php";
   } else if ($access == 'guest') {
      require "./inputs-form/guest-login.php";

   } else {
      require "./inputs-form/main-user.php";

   }

}
