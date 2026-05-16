<?php
require_once "includes/auth.php";

if (isset($_SESSION["usuario_id"])) {
    header("Location: perfil.php");
} else {
    header("Location: login.php");
}
exit;
?>
