<?php
require_once __DIR__ . "/auth.php";
$pagina_actual = basename($_SERVER["PHP_SELF"]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Perfil de Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
<nav class="bg-indigo-600 text-white shadow-lg shadow-indigo-600/15">
    <div class="mx-auto flex min-h-14 w-[92%] max-w-6xl flex-col justify-center gap-2 py-3 sm:min-h-14 sm:flex-row sm:items-center sm:justify-between sm:py-0">
        <a class="text-xl font-bold" href="index.php">Sistema de Perfil</a>
        <ul class="flex flex-wrap items-center gap-2 text-sm font-medium">
            <?php if (isset($_SESSION["usuario_id"])): ?>
                <li><a class="rounded-md px-3 py-1.5 transition hover:bg-white/15 <?= $pagina_actual === "perfil.php" ? "bg-white/15 text-white" : "text-white/80" ?>" href="perfil.php">Perfil</a></li>
                <li><a class="rounded-md px-3 py-1.5 transition hover:bg-white/15 <?= $pagina_actual === "cambiar_password.php" ? "bg-white/15 text-white" : "text-white/80" ?>" href="cambiar_password.php">Cambiar contrase&ntilde;a</a></li>
                <li><a class="rounded-md px-3 py-1.5 text-white/80 transition hover:bg-white/15 hover:text-white" href="logout.php">Cerrar sesi&oacute;n</a></li>
            <?php else: ?>
                <li><a class="rounded-md px-3 py-1.5 transition hover:bg-white/15 <?= $pagina_actual === "login.php" ? "bg-white/15 text-white" : "text-white/80" ?>" href="login.php">Login</a></li>
                <li><a class="rounded-md px-3 py-1.5 transition hover:bg-white/15 <?= $pagina_actual === "register.php" ? "bg-white/15 text-white" : "text-white/80" ?>" href="register.php">Registro</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<main class="mx-auto my-6 w-[92%] max-w-5xl sm:my-8">
