<?php
session_start();

if(isset($_SESSION['user_id'])) {

    # Apagar as variáveis de sessão ao limpar o array $_SESSION
    $_SESSION = array();

    # Elimina a sessão ao definir a sua data de expiração para uma hora atrás
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600);
    }
    # Obliterar a sessão
    session_destroy();
}

# Eliminar os cookies ao definir a sua data de expiração para uma hora atrás
setcookie('user_id', '', time() - 3600);
setcookie('username', '', time() - 3600);
setcookie('birthday_notification', '',time() - 3600);
setcookie('project_notification', '',time() - 3600);

# Redireccionar o utilizador para a página de login
$login_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
header('Location: ' . $login_url);
?>