<?php
require_once "config/database.php";
require_once "includes/auth.php";
redirect_if_logged();

$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($correo === "" || $password === "") {
        $errores[] = "Debe ingresar correo y contraseña.";
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo ingresado no tiene un formato válido.";
    }

    if (empty($errores)) {
        $stmt = $pdo->prepare("SELECT id, nombre, correo, password FROM usuarios WHERE correo = :correo LIMIT 1");
        $stmt->execute([":correo" => $correo]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario["password"])) {
            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nombre"] = $usuario["nombre"];
            $_SESSION["usuario_correo"] = $usuario["correo"];

            header("Location: perfil.php");
            exit;
        } else {
            $errores[] = "Correo o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Perfil de Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-slate-100 font-sans text-slate-900 antialiased">
<main class="flex min-h-screen items-center justify-center px-4 py-8">
<div class="w-full max-w-sm">

<div class="mb-5 grid grid-cols-2 gap-3">
    <a class="rounded-2xl bg-indigo-600 px-4 py-3 text-center text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700" href="login.php">Login</a>
    <a class="rounded-2xl border border-indigo-200 bg-white px-4 py-3 text-center text-sm font-bold text-indigo-700 shadow-sm transition hover:bg-indigo-50 hover:text-indigo-950" href="register.php">Register</a>
</div>
<div>
    <div>
        <section class="rounded-3xl border border-indigo-100 bg-white/95 p-8 shadow-2xl shadow-indigo-200/50">
            <div class="mb-7 text-center">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-indigo-500">Bienvenido</p>
                <h3 class="text-3xl font-black text-slate-950">Inicio de sesión</h3>
            </div>

            <?php foreach ($errores as $error): ?>
                    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?= e($error) ?></div>
            <?php endforeach; ?>

            <form class="space-y-5" method="POST" action="">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700" for="loginCorreo">Email o usuario</label>
                    <input type="email" id="loginCorreo" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" name="correo" value="<?= e($_POST["correo"] ?? "") ?>" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700" for="loginPassword">Contraseña</label>
                    <input type="password" id="loginPassword" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" name="password" />
                </div>

                <div class="grid gap-3 text-sm sm:grid-cols-2">
                    <div class="flex justify-center sm:justify-start">
                        <div class="flex items-center gap-2">
                            <input class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" type="checkbox" value="" id="loginCheck" checked />
                            <label class="text-slate-700" for="loginCheck">Recordarme</label>
                        </div>
                    </div>
                    <div class="flex justify-center sm:justify-end">
                        <a class="font-semibold text-indigo-700 hover:text-indigo-950 hover:underline" href="cambiar_password.php">¿Olvidó la contraseña?</a>
                    </div>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">Ingresar</button>

                <div class="text-center text-sm text-slate-600">
                    <p>¿No es miembro? <a class="font-bold text-indigo-700 hover:text-indigo-950 hover:underline" href="register.php">Registrarse</a></p>
                </div>
            </form>
        </section>
    </div>
</div>

</div>
</main>
</body>
</html>
