<?php
require_once "config/database.php";
require_once "includes/auth.php";
redirect_if_logged();

$errores = [];
$exito = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cedula = trim($_POST["cedula"] ?? "");
    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmar_password = $_POST["confirmar_password"] ?? "";

    if ($cedula === "" || $nombre === "" || $correo === "" || $password === "" || $confirmar_password === "") {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo ingresado no tiene un formato válido.";
    }

    if (strlen($password) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    if ($password !== $confirmar_password) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    if (empty($errores)) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = :correo OR cedula = :cedula LIMIT 1");
        $stmt->execute([
            ":correo" => $correo,
            ":cedula" => $cedula
        ]);
        $usuario_existente = $stmt->fetch();

        if ($usuario_existente) {
            $errores[] = "Ya existe un usuario registrado con ese correo o cédula.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO usuarios (cedula, nombre, correo, password)
                VALUES (:cedula, :nombre, :correo, :password)
            ");

            $stmt->execute([
                ":cedula" => $cedula,
                ":nombre" => $nombre,
                ":correo" => $correo,
                ":password" => $password_hash
            ]);

            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $pdo->lastInsertId();
            $_SESSION["usuario_nombre"] = $nombre;
            $_SESSION["usuario_correo"] = $correo;

            header("Location: perfil.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Sistema de Perfil de Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-slate-100 font-sans text-slate-900 antialiased">
<main class="flex min-h-screen items-center justify-center px-4 py-8">
<div class="w-full max-w-md">

<div class="mb-5 grid grid-cols-2 gap-3">
    <a class="rounded-2xl border border-indigo-200 bg-white px-4 py-3 text-center text-sm font-bold text-indigo-700 shadow-sm transition hover:bg-indigo-50 hover:text-indigo-950" href="login.php">Login</a>
    <a class="rounded-2xl bg-indigo-600 px-4 py-3 text-center text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700" href="register.php">Register</a>
</div>
<div>
    <div>
        <section class="rounded-3xl border border-indigo-100 bg-white/95 p-8 shadow-2xl shadow-indigo-200/50">
            <div class="mb-7 text-center">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-indigo-500">Crear cuenta</p>
                <h3 class="text-3xl font-black text-slate-950">Registro de usuario</h3>
            </div>

            <?php foreach ($errores as $error): ?>
                    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?= e($error) ?></div>
            <?php endforeach; ?>

            <?php if ($exito): ?>
                    <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?= e($exito) ?></div>
            <?php endif; ?>

            <form class="space-y-5" method="POST" action="">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700" for="registerCedula">Cédula</label>
                    <input type="text" id="registerCedula" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" name="cedula" value="<?= e($_POST["cedula"] ?? "") ?>" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700" for="registerNombre">Nombre</label>
                    <input type="text" id="registerNombre" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" name="nombre" value="<?= e($_POST["nombre"] ?? "") ?>" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700" for="registerCorreo">Correo</label>
                    <input type="email" id="registerCorreo" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" name="correo" value="<?= e($_POST["correo"] ?? "") ?>" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700" for="registerPassword">Contraseña</label>
                    <input type="password" id="registerPassword" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" name="password" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700" for="registerRepeatPassword">Repetir contraseña</label>
                    <input type="password" id="registerRepeatPassword" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" name="confirmar_password" />
                </div>

                <div class="flex items-start justify-center gap-2 text-sm">
                    <input class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" type="checkbox" value="" id="registerCheck" checked aria-describedby="registerCheckHelpText" />
                    <label class="text-slate-700" for="registerCheck">He leído y acepto los términos</label>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">Registrarse</button>
            </form>
        </section>
    </div>
</div>

</div>
</main>
</body>
</html>
