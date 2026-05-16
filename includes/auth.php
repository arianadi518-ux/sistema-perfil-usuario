<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($text) {
    return htmlspecialchars($text ?? "", ENT_QUOTES, "UTF-8");
}

function require_login() {
    if (!isset($_SESSION["usuario_id"])) {
        header("Location: login.php");
        exit;
    }
}

function redirect_if_logged() {
    if (isset($_SESSION["usuario_id"])) {
        header("Location: perfil.php");
        exit;
    }
}
?>
