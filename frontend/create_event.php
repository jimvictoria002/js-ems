<?php
$title = 'Create event';
require "../connection.php";
require "./partials/header.php";
require "./components/side-nav.php";
?>
<main class="w-full h-screen overflow-auto">

    <?php
    $active = 'event';
    require "./components/top-nav.php";
    require "./components/inputs-form/create-event.php";
    ?>

</main>

<?php
require "./partials/footer.php";
?>