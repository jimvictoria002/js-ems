<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

$title = 'Users';
require "../connection.php";
require "./partials/header.php";
$active = 'users';
require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50 ">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 mb-40">
        <div class="bg-white p-4 w-full flex flex-col">
            <p class="text-xl font-semibold md:text-3xl my-8 mt-3">Users</p>
            
            <?php
            ?>
        </div>

    </div>

    <?php
    if (isset($_SESSION['success'])):
        require "./components/success-message.php";
        unset($_SESSION['success']);
    endif;
    ?>

    <?php
    require "./components/modals/create-event-modal.php";
    ?>

    <?php
    require "./components/modals/view-event-modal.php";
    ?>


</main>

<?php
require "./partials/footer.php";
?>