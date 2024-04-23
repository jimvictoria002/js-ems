<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("HTTP/1.0 404 NOT FOUND");
    exit;
}

$title = 'Dashboard';
require "../connection.php";
require "./partials/header.php";
$active = 'dashboard';

require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50">
    <?php
    require "./components/top-nav.php";
    ?>

    <?php
    if (isset($_SESSION['success'])):
        require "./components/success-message.php";
        unset($_SESSION['success']);
    endif;
    ?>

</main>

<?php
require "./partials/footer.php";
?>