<?php
require "../connection.php";
session_start();

$access = $_SESSION['access'];

if (!($access == 'admin' || $access == 'teacher')){
    header('Location: dashboard.php');
    exit;
}


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


if (!isset($_GET['event_id'])) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

if (!is_numeric($_GET['event_id'])) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

$event_id = $_GET['event_id'];

$query = "SELECT * FROM events WHERE event_id = $event_id";
$result = $conn->query($query);
$event = $result->fetch_assoc();

if ($result->num_rows < 1) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}


$title = $event['title'];
$create_by = $event['created_by'];

if ($_SESSION['access'] != 'admin') {
    if ($create_by != $_SESSION['user_id']) {
        header('HTTP/1.0 404 NOT FOUND');
        exit;
    }
}


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