<?php
# Iniciar variávies de sessão
require_once('startsession.php');

# Script para redirecionar caso o utilizador não esteja autenticado
require_once ('redirect.php');

# Inclui variáveis de conexão à bd
require_once('connectvars.php');

# Eliminar utilizador
if(isset($_GET['user_id'])) {

    if(!isset($_SESSION['user_id'])) {
        exit();
    }
    else {

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        $query = "DELETE FROM intranet_user WHERE user_id = '" . $_GET['user_id'] . "'";


        mysqli_query($dbc, $query);

        # Fechar conexão à bd
        mysqli_close($dbc);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

}
else if(isset($_GET['color_id'])) {

    if(!isset($_SESSION['user_id'])) {
        echo '<h2>Não tem permissão para aceder a esta página</h2>';
        exit();
    }
    else {

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        # Apagar cor da tabela
        $query = "DELETE FROM intranet_color WHERE color_id = '" . $_GET['color_id'] . "'";
        mysqli_query($dbc, $query);

        # Obter o user_id do utilizador que ficou sem cor atribuida
        $query = "SELECT user_id FROM intranet_user WHERE color = '" . $_GET['color_id'] . "'";
        $data = mysqli_query($dbc, $query);
        $fetch = mysqli_fetch_assoc($data);
        $value = $fetch['user_id'];

        # Actualizar a cor do utilizador que ficou sem cor atribuida para o valor default(branco)
        $query = "UPDATE intranet_user SET color = 1 WHERE user_id = '$value'";
        mysqli_query($dbc, $query);

        # Fechar conexão à bd
        mysqli_close($dbc);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
else if(isset($_GET['vacation_id'])) {

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $vacation_id = $_GET['vacation_id'];

    $query = "DELETE FROM intranet_vacation WHERE vacation_id = '$vacation_id'";

    mysqli_query($dbc, $query);

    # Fechar conexão
    mysqli_close($dbc);

    header('Location: ' . $_SERVER['HTTP_REFERER']);


}