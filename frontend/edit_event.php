<?php
require "../connection.php";
session_start();

if(!isset($_GET['event_id'])){
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

if(!is_numeric($_GET['event_id'])){
    header('HTTP/1.0 404 NOT FOUND');
    exit;

}

$event_id =  $_GET['event_id'];

$query ="SELECT * FROM events WHERE event_id = $event_id";
$result = $conn->query($query);
$event = $result->fetch_assoc();

if($result->num_rows < 1){
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}


$title = 'Create event';
require "./partials/header.php";
require "./components/side-nav.php";
?>
<main class="w-full h-screen overflow-auto">

    <?php
    $active = 'event';
    require "./components/top-nav.php";
    require "./components/inputs-form/edit-event.php";
    ?>

</main>

<?php
require "./partials/footer.php";
?>