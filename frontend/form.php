<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

$fullname = $_SESSION['firstname'] . ($_SESSION['middlename'] ? ' ' .  $_SESSION['middlename'] : '') . ' ' . $_SESSION['lastname'];

if (!isset($_GET['r_f_id']) || !is_numeric($_GET['r_f_id'])) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

require "../connection.php";


$r_f_id = $_GET['r_f_id'];
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM 
            `response_form` rf 
         INNER JOIN forms f ON 
            rf.f_id = f.f_id 
         WHERE rf.r_f_id = $r_f_id AND rf.response_id = $user_id";

$result = $conn->query($query);

if($result->num_rows < 1){
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

$response_form = $result->fetch_assoc();

$form_title = $response_form['title'] ? $response_form['title'] : 'Untitled form';
$f_id = $response_form['f_id'];
$event_description = $response_form['description'] ? $response_form['description'] : '';

$query = "SELECT
            r.r_id,
            r.answer,
            q.q_id,
            q.question,
            q.type,
            q.required
        FROM 
            questionnaire q
        LEFT JOIN `response` r  ON
            r.q_id = q.q_id
        WHERE
            r.r_f_id = $r_f_id
        ORDER BY q.created_at ";
$result = $conn->query($query);


$title = "$form_title";
require "./partials/header.php";
$active = '';
require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50 ">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 mb-40">
        <div class="bg-white p-4 w-full flex flex-col">
            <?php
            require "./components/inputs-form/answer-form.php";
            ?>
        </div>

    </div>

    <?php
    if (isset($_SESSION['success'])) :
        require "./components/success-message.php";
        unset($_SESSION['success']);
    endif;
    ?>
</main>

<?php
require "./partials/footer.php";
?>