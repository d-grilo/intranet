<?php
# Iniciar variávies de sessão
require_once('startsession.php');

# Script para redirecionar caso o utilizador não esteja autenticado
require_once ('redirect.php');

# Inclui variáveis de conexão à bd
require_once('connectvars.php');

# Eliminar categoria
if(isset($_GET['category_id'])) {

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $query = "DELETE FROM intranet_wiki_category WHERE category_id = '" . $_GET['category_id'] . "'";

    mysqli_query($dbc, $query);

    # Fechar conexão à bd
    mysqli_close($dbc);

    $url_enc = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/wiki.php';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    //header('Location: ' . $url_enc);
}

# Eliminar artigo
else if(isset($_GET['article_id'])) {

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $query = "DELETE FROM intranet_wiki_article WHERE article_id = '" . $_GET['article_id'] . "'";

    mysqli_query($dbc, $query);

    # Fechar conexão
    mysqli_close($dbc);

    $url_enc = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/wiki.php';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    //header('Location: ' . $url_enc);

}




?>