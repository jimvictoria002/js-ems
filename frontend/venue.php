<?php

session_start();

$access = $_SESSION['access'];

if (!($access == 'admin' || $access == 'teacher')){
    header('Location: dashboard.php');
    exit;
}


if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
}


$title = 'Venue';
require "../connection.php";
require "./partials/header.php";
$active = 'venue';

require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 ">
        <div class="bg-white p-4 w-full flex flex-col">
            <p class="text-xl font-semibold md:text-3xl my-8 mt-3">Venue</p>

            <?php
            require "./components/tables/events-venue-table.php";
            ?>
        </div>

    </div>

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