<?php
session_start();
require_once('../connectvars.php');


$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$note_type = mysqli_real_escape_string($dbc, trim($_POST['note_type']));
$note_date = mysqli_real_escape_string($dbc, trim($_POST['note_date']));
$note_content = mysqli_real_escape_string($dbc, trim($_POST['note_content']));

$user_id = $_SESSION['user_id'];

# Só continuar caso todos os campos estejam preenchidos
if (isset($note_type) && !empty($note_date) && !empty($note_content)) {

    $query = "INSERT INTO intranet_note(note_content, note_type, note_date, user_id)" .
        "VALUES('$note_content', '$note_type', '$note_date', '$user_id')";

    mysqli_query($dbc, $query);

    echo json_encode(1); // correu tudo bem

}
else {
    echo json_encode(2); // campos vazios
}

