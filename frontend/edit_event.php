<?php
require "../connection.php";
session_start();

$access = $_SESSION['access'];

if (!($access == 'admin' || $access == 'teacher' || $access == 'staff'|| $access == 'student')) {
    header('Location: dashboard.php');
    exit;
}


if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
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
$created_by = $event['created_by'];


$hasAccess = ($event['created_by'] == $_SESSION['user_id'] || ($_SESSION['access'] == 'admin' || $_SESSION['access'] == 'staff'));

if (!$hasAccess) {
    $q_ea = "SELECT * FROM event_access WHERE event_id = $event_id";
    $r_eq = $conn->query($q_ea);
    while ($event_acess = $r_eq->fetch_assoc()) {
        $hasAccess = ($event_acess['user_id'] == $_SESSION['user_id'] && $event_acess['access'] == $_SESSION['access']);
        if ($hasAccess) {
            break;
        }
    }
}

if (!$hasAccess) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
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