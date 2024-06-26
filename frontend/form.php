<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}


if (!isset($_GET['r_f_id']) || !is_numeric($_GET['r_f_id'])) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

require "../connection.php";


$r_f_id = $_GET['r_f_id'];
$user_id = $_SESSION['user_id'];
$access = $_SESSION['access'];

$query = "SELECT
            rf.*,
            f.*,
            e.created_by,
            rd.*
         FROM
            `response_form` rf
         INNER JOIN events e ON
            rf.event_id = e.event_id
         INNER JOIN forms f ON 
            e.f_id = f.f_id
         INNER JOIN respondent_data rd ON
            rf.r_f_id = rd.r_f_id
         WHERE rf.r_f_id = $r_f_id";

$result = $conn->query($query);


if ($result->num_rows < 1) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

$response_form = $result->fetch_assoc();

$form_title = $response_form['title'] ? $response_form['title'] : 'Untitled form';
$f_id = $response_form['f_id'];
$respondent = $response_form['respondent'];
$response_id = $response_form['response_id'];
$created_by = $response_form['created_by'];
$firstname = $response_form['firstname'];
$lastname = $response_form['lastname'];
$middlename = $response_form['middlename'];
$email = $response_form['email'];

$fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;


if (!($access == 'admin' || $access == 'staff')) {
    if (($created_by != $user_id) && $response_id != $user_id) {
        header('HTTP/1.0 404 NOT FOUND');
        exit;
    } 
}
$event_description = $response_form['description'] ? $response_form['description'] : '';
$is_done = $response_form['is_done']  == 'yes' ? true : false;


$query = "SELECT
            q.q_id,
            q.question,
            (
            SELECT
                answer
            FROM
                response r
            WHERE
                r.q_id = q.q_id AND r.r_f_id = $r_f_id 
            ) AS answer,
            q.type,
            q.required
        FROM
            (
            SELECT
                q.q_id,
                q.question,
                q.type,
                q.required
            FROM
                questionnaire q
            WHERE
                q.f_id = $f_id
        ORDER BY
                q.created_at
            ) AS q";
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