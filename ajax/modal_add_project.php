<?php
session_start();
require_once('../connectvars.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$project_name = mysqli_real_escape_string($dbc, trim($_POST['project_name']));
$project_start_date = mysqli_real_escape_string($dbc, trim($_POST['project_start_date']));
$project_end_date = mysqli_real_escape_string($dbc, trim($_POST['project_end_date']));
$project_summary = mysqli_real_escape_string($dbc, trim($_POST['project_summary']));

$user_id = $_SESSION['user_id'];

# Só continuar caso pelo menos o nome, data inicio e data de fim estejam preenchidos
if(!empty($project_name) && !empty($project_start_date) && !empty($project_end_date)) {

    $query = "SELECT project_name FROM intranet_project WHERE project_name = '$project_name'";

    $data = mysqli_query($dbc, $query);

    # Apenas criar o projecto caso não exista outro com o mesmo nome
    if (mysqli_num_rows($data) == 0) {

        $query_project = "INSERT INTO intranet_project(project_name, project_start_date, project_end_date, project_summary)" .
            "VALUES ('$project_name', '$project_start_date', '$project_end_date', '$project_summary')";

        mysqli_query($dbc, $query_project);

        # Obter o último project_id para inserir na próxima query
        $query_project_id = "SELECT MAX(project_id) last_id FROM intranet_project";

        $data_project_id = mysqli_query($dbc, $query_project_id);

        $project_id = mysqli_fetch_assoc($data_project_id);

        $value = $project_id['last_id'];

        /* Verifica se foi atribuido algum utilizador ao projecto, caso tenha sido
        mudar o identificador do progresso */

        if (isset($_POST['users'])) {

            $query = "UPDATE intranet_project SET project_progress = 1 WHERE project_id = '$value'";

            mysqli_query($dbc, $query);

            # Atribuir os utilizadores selecionados ao último projecto criado
            foreach ($_POST['users'] as $_boxValue) {

                $query = "INSERT INTO intranet_assigned_project(project_id, user_id)" .
                    "VALUES ('$value', '$_boxValue')";

                mysqli_query($dbc, $query);

            } // foreach

        } // post users

        echo json_encode(1); // correu tudo bem

    } else {
        echo json_encode(2); // já existe um projecto com este nome
    }

}
else {
    echo json_encode(3); // existem campos vazios
}

# Fechar ligaão
mysqli_close($dbc);



