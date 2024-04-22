<?php
$title = 'Create event';
require "../connection.php";
require "./partials/header.php";
require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen">
    <?php
    $active = 'event';
    require "./components/top-nav.php";
    require "./components/render-calendar.php";
    ?>

</main>

<?php
require "./partials/footer.php";
?>