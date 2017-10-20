<?php
# Verificar se o utilizador está autenticado antes de continuar
if($page_title != 'Login') {
    if (!isset($_SESSION['user_id'])) {
        // Redireccionar o utilizador para a página de login caso não esteja
        $redirect_login = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
        header('Location: ' . $redirect_login);

    }
}
else if($page_title == 'Login') {
    if(isset($_SESSION['user_id'])) {
        // Redireccionar o utilizador para o dashboard
        $redirect_login = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
        header('Location: ' . $redirect_login);
    }
}

?>