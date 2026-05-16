<?php
require_once "config/database.php";
require_once "includes/auth.php";
require_login();

$errores = [];
$exito = "";

$stmt = $pdo->prepare("SELECT cedula, nombre, correo, fecha_registro FROM usuarios WHERE id = :id LIMIT 1");
$stmt->execute([":id" => $_SESSION["usuario_id"]]);
$usuario = $stmt->fetch();

if (!$usuario) {
    session_destroy();
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");

    if ($nombre === "" || $correo === "") {
        $errores[] = "El nombre y el correo no pueden estar vacíos.";
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo ingresado no tiene un formato válido.";
    }

    if (empty($errores)) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = :correo AND id != :id LIMIT 1");
        $stmt->execute([
            ":correo" => $correo,
            ":id" => $_SESSION["usuario_id"]
        ]);

        if ($stmt->fetch()) {
            $errores[] = "El correo ya está registrado por otro usuario.";
        } else {
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = :nombre, correo = :correo WHERE id = :id");
            $stmt->execute([
                ":nombre" => $nombre,
                ":correo" => $correo,
                ":id" => $_SESSION["usuario_id"]
            ]);

            $_SESSION["usuario_nombre"] = $nombre;
            $_SESSION["usuario_correo"] = $correo;

            $exito = "Datos actualizados correctamente.";

            $stmt = $pdo->prepare("SELECT cedula, nombre, correo, fecha_registro FROM usuarios WHERE id = :id LIMIT 1");
            $stmt->execute([":id" => $_SESSION["usuario_id"]]);
            $usuario = $stmt->fetch();
        }
    }
}
?>

<?php include "includes/header.php"; ?>

<section class="rounded-3xl border border-indigo-100 bg-white p-6 shadow-2xl shadow-indigo-100/70 sm:p-8">
    <h2 class="mb-5 text-3xl font-black text-slate-950">Perfil de usuario</h2>

    <?php foreach ($errores as $error): ?>
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?= e($error) ?></div>
    <?php endforeach; ?>

    <?php if ($exito): ?>
        <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?= e($exito) ?></div>
    <?php endif; ?>

    <div class="mb-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-5 text-slate-800">
        <p class="mb-3"><strong class="font-semibold text-slate-950">Cédula:</strong> <?= e($usuario["cedula"]) ?></p>
        <p class="mb-3"><strong class="font-semibold text-slate-950">Nombre:</strong> <?= e($usuario["nombre"]) ?></p>
        <p class="mb-3"><strong class="font-semibold text-slate-950">Correo:</strong> <?= e($usuario["correo"]) ?></p>
        <p><strong class="font-semibold text-slate-950">Fecha de registro:</strong> <?= e($usuario["fecha_registro"]) ?></p>
    </div>

    <h3 class="mb-4 text-2xl font-semibold text-slate-950">Actualizar datos</h3>

    <form method="POST" action="">
        <div class="mb-4">
            <label for="nombre" class="mb-1.5 block text-sm font-semibold text-slate-800">Nombre completo</label>
            <input type="text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" id="nombre" name="nombre" value="<?= e($usuario["nombre"]) ?>">
        </div>

        <div class="mb-5">
            <label for="correo" class="mb-1.5 block text-sm font-semibold text-slate-800">Correo electrónico</label>
            <input type="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" id="correo" name="correo" value="<?= e($usuario["correo"]) ?>">
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
            <button type="submit" class="rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">Guardar cambios</button>
            <a class="rounded-2xl bg-slate-700 px-5 py-3 text-center text-sm font-bold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200" href="cambiar_password.php">Cambiar contraseña</a>
        </div>
    </form>
</section>

<?php include "includes/footer.php"; ?>
