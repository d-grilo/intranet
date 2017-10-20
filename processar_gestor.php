<?php
# Iniciar variávies de sessão
require_once('startsession.php');

# Script para redirecionar caso o utilizador não esteja autenticado
require_once ('redirect.php');

# Inclui variáveis de conexão à bd
require_once('connectvars.php');

# Adicionar utilizador ao projecto
if(isset($_GET['user_id'], $_GET['project_id']) && $_GET['add'] == 1) {

    # temos de verificar se já existe uma relação entre este utilizador e o projecto
    $user_id = $_GET['user_id'];
    $project_id = $_GET['project_id'];

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3>Falha ao conectar com a base de dados</h3>');


    $query = "SELECT * FROM intranet_assigned_project WHERE user_id = '$user_id' AND project_id = '$project_id'";

    $data = mysqli_query($dbc, $query)
        or die('<h3>Falha co comunicar com a base de dados</h3>');

    if(mysqli_num_rows($data) == 0) {

        $query = "INSERT INTO intranet_assigned_project(user_id, project_id)" .
            "VALUES('$user_id', '$project_id')";

        mysqli_query($dbc, $query)
        or die('<h3>Falha ao comunicar com a base de dados</h3>');

        $query = "UPDATE intranet_project SET project_progress = 1 WHERE project_id = '$project_id'";

        mysqli_query($dbc, $query)
        or die('<h3>Falha ao comunicar com a base de dados</h3>');


        mysqli_close($dbc);

        $_SESSION['project_edit'] = 1;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    else {
        // criar variável de erro e rencaminhar
        $_SESSION['project_edit'] = 2;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

}
# Remover utilizador do projecto
else if(isset($_GET['user_id'], $_GET['project_id']) && $_GET['delete'] == 1) {

    $user_id = $_GET['user_id'];
    $project_id = $_GET['project_id'];

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3>Falha ao conectar à base de dados</h3>');

    $query = "DELETE FROM intranet_assigned_project WHERE user_id = '$user_id' AND project_id = '$project_id'";

    $data = mysqli_query($dbc, $query)
        or die('<h3>Falha ao comunicar com a base de dados</h3>');

    if(mysqli_affected_rows($dbc) == 0)
        $_SESSION['project_edit'] = 4;
    else
        $_SESSION['project_edit'] = 3;


    mysqli_close($dbc);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

# Eliminar projecto
else if (isset($_GET['project_id']) && $_GET['delete'] == 2) {

    $project_id = $_GET['project_id'];

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3>Falha ao conectar à base de dados</h3>');

    $query = "DELETE FROM intranet_project WHERE project_id = '$project_id'";

    mysqli_query($dbc, $query)
        or die('<h3>Falha ao comunicar com a base de dados</h3>');

    mysqli_close($dbc);


    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
# Marcar projecto como completado
else if (isset($_GET['project_id']) && $_GET['complete'] == 1) {

    $project_id = $_GET['project_id'];

    $dbc = mysqli_connect(DB_HOST,DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3>Falha ao conectar à base de dados</h3>');

    $query = "UPDATE intranet_project SET project_progress = 2 WHERE project_id = '$project_id'";

    mysqli_query($dbc, $query)
    or die('<h3>Falha ao comunicar com a base de dados</h3>');

    mysqli_close($dbc);


    header('Location: ' . $_SERVER['HTTP_REFERER']);

}
# Colocar projecto em progresso
else if (isset($_GET['project_id']) && isset($_GET['progress'])) {

    $project_id = $_GET['project_id'];

    $dbc = mysqli_connect(DB_HOST,DB_USER, DB_PASSWORD, DB_NAME)
    or die('<h3>Falha ao conectar à base de dados</h3>');

    $query = "UPDATE intranet_project SET project_progress = 1 WHERE project_id = '$project_id'";

    mysqli_query($dbc, $query)
    or die('<h3>Falha ao comunicar com a base de dados</h3>');

    mysqli_close($dbc);


    header('Location: ' . $_SERVER['HTTP_REFERER']);

}




/******************************* TAREFAS **************************************/
# Eliminar tarefa
else if (isset($_GET['task_id']) && $_GET['delete'] == 2) {

    $task_id = $_GET['task_id'];

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3>Falha ao conectar à base de dados</h3>');

    $query = "DELETE FROM intranet_task WHERE task_id = '$task_id'";

    mysqli_query($dbc, $query)
        or die('<h3>Falha ao comunicar com a base de dados</h3>');

    mysqli_close($dbc);

    header('Location: ' . $_SERVER['HTTP_REFERER']);

}
# Marcar tarefa como completa
else if(isset($_GET['task_id']) && $_GET['complete'] == 1) {

    $task_id = $_GET['task_id'];

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3>Falha ao conectar à base de dados</h3>');

    $query = "UPDATE intranet_task SET task_progress = 2 WHERE task_id = '$task_id'";

    mysqli_query($dbc, $query)
     or die('<h3>Falha ao comunicar com a base de dados</h3>');

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

# Adicionar utilizador à tarefa
else if(isset($_GET['user_id'], $_GET['task_id']) && $_GET['add'] == 1) {

    # temos de verificar se já existe uma relação entre este utilizador e a tarefa
    $user_id = $_GET['user_id'];
    $task_id = $_GET['task_id'];

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3>Falha ao conectar com a base de dados</h3>');


    $query = "SELECT * FROM intranet_assigned_task WHERE user_id = '$user_id' AND task_id = '$task_id'";

    $data = mysqli_query($dbc, $query)
        or die('<h3>Falha co comunicar com a base de dados</h3>');

    if(mysqli_num_rows($data) == 0) {

        $query = "INSERT INTO intranet_assigned_task(user_id, task_id)" .
            "VALUES('$user_id', '$task_id')";

        mysqli_query($dbc, $query)
            or die('<h3>Falha ao comunicar com a base de dados</h3>');

        $query = "UPDATE intranet_task SET task_progress = 1 WHERE task_id = '$task_id'";

        mysqli_query($dbc, $query)
            or die('<h3>Falha ao comunicar com a base de dados</h3>');


        mysqli_close($dbc);

        $_SESSION['task_edit'] = 1;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    else {
        // criar variável de erro e rencaminhar
        $_SESSION['task_edit'] = 2;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }


}

# Remover utilizador da tarefa
else if(isset($_GET['user_id'], $_GET['task_id']) && $_GET['delete'] == 1) {

    $user_id = $_GET['user_id'];
    $task_id = $_GET['task_id'];

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3>Falha ao conectar à base de dados</h3>');

    $query = "DELETE FROM intranet_assigned_task WHERE user_id = '$user_id' AND task_id = '$task_id'";

    $data = mysqli_query($dbc, $query)
        or die('<h3>Falha ao comunicar com a base de dados</h3>');

   if(mysqli_affected_rows($dbc) == 0)
       $_SESSION['task_edit'] = 4;
   else
       $_SESSION['task_edit'] = 3;

    mysqli_close($dbc);

    header('Location: ' . $_SERVER['HTTP_REFERER']);

}

