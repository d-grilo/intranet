<?php
session_start();
require_once('../connectvars.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$task_name = mysqli_real_escape_string($dbc, trim($_POST['task_name']));
$task_project = mysqli_real_escape_string($dbc, trim($_POST['selected_project']));
$task_start_date = mysqli_real_escape_string($dbc, trim($_POST['task_start_date']));
$task_end_date = mysqli_real_escape_string($dbc, trim($_POST['task_end_date']));
$task_summary = mysqli_real_escape_string($dbc, trim($_POST['task_summary']));

$user_id = $_SESSION['user_id'];

# Só continuar caso os campos necessários estejam preenchidos
if(!empty($task_name) && isset($task_project) && !empty($task_start_date) && !empty($task_end_date)) {

    $query = "SELECT task_name FROM intranet_task WHERE task_name = '$task_name'";

    $data = mysqli_query($dbc, $query);

    # Apenas criar a tarefa caso não exista outra com o mesmo nome
    if (mysqli_num_rows($data) == 0) {

        $query = "INSERT INTO intranet_task(task_name, task_start_date, task_end_date, project_id, task_summary)" .
            "VALUES('$task_name', '$task_start_date', '$task_end_date', '$task_project', '$task_summary')";

        mysqli_query($dbc, $query);

        # Obter o último task_id para inserir na pŕoxima query
        $query_task_id = "SELECT MAX(task_id) last_id FROM intranet_task";

        $data_task_id = mysqli_query($dbc, $query_task_id);

        $task_id = mysqli_fetch_assoc($data_task_id);

        $value = $task_id['last_id'];

        /* Verifica se foi atribuido algum utilizador à tarefa,
        caso tenha sido, mudar o identificador do progresso */
        if (isset($_POST['users'])) {

            $query = "UPDATE intranet_task SET task_progress = 1 WHERE task_id = '$value'";

            mysqli_query($dbc, $query);

            # Atribuir os utilizadores selecionados ao último projecto criado
            foreach ($_POST['users'] as $_boxValue) {

                $query = "INSERT INTO intranet_assigned_task(task_id, user_id)" .
                    "VALUES('$value', '$_boxValue')";

                mysqli_query($dbc, $query);

            } // end foreach

        } // post users

        echo json_encode(1); // correu tudo bem

    } // ja existe uma tarefa com este nome
    else {
        echo json_encode(2); // tarefa com o mesmo nome
    }


} // empty fields
else {
    echo json_encode(3); // campos vazios
}

