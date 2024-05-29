<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

$access = $_SESSION['access'];

if (!($access == 'admin' || $access == 'teacher' || $access == 'staff' || $access == 'student')) {
    header('Location: dashboard.php');
    exit;
}


$title = 'My forms';
require "../connection.php";
require "./partials/header.php";
$active = '';

require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 mb-40">
        <div class="bg-white p-4 w-full flex flex-col">

            <?php
            require "./components/inputs-form/evaluation-form.php";
            ?>

        </div>

    </div>

    <?php
    if (isset($_SESSION['success'])) :
        require "./components/success-message.php";
        unset($_SESSION['success']);
    endif;
    ?>
    <?php
    if (isset($_SESSION['failed'])) :
        require "./components/failed-message.php";
        unset($_SESSION['failed']);
    endif;
    ?>




</main>

<?php
require "./partials/footer.php";
?>