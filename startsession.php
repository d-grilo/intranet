<?php
ob_start();
session_start();
# Se as variáveis de sessão não existirem, tentar defini-las com os cookies do utilizador
if(!isset($_SESSION['user_id'])) {
    if(isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['level'] = $_COOKIE['level'];
    }
}
?>