<?php
require_once "config/database.php";
require_once "includes/auth.php";
require_login();

$errores = [];
$exito = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password_actual = $_POST["password_actual"] ?? "";
    $password_nuevo = $_POST["password_nuevo"] ?? "";
    $confirmar_password = $_POST["confirmar_password"] ?? "";

    if ($password_actual === "" || $password_nuevo === "" || $confirmar_password === "") {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (strlen($password_nuevo) < 6) {
        $errores[] = "La nueva contraseña debe tener al menos 6 caracteres.";
    }

    if ($password_nuevo !== $confirmar_password) {
        $errores[] = "La nueva contraseña y la confirmación no coinciden.";
    }

    if (empty($errores)) {
        $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute([":id" => $_SESSION["usuario_id"]]);
        $usuario = $stmt->fetch();

        if (!$usuario || !password_verify($password_actual, $usuario["password"])) {
            $errores[] = "La contraseña actual es incorrecta.";
        } else {
            $nuevo_hash = password_hash($password_nuevo, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE usuarios SET password = :password WHERE id = :id");
            $stmt->execute([
                ":password" => $nuevo_hash,
                ":id" => $_SESSION["usuario_id"]
            ]);

            $exito = "La contraseña fue actualizada correctamente.";
        }
    }
}
?>

<?php include "includes/header.php"; ?>

<section class="mx-auto max-w-2xl rounded-3xl border border-indigo-100 bg-white p-6 shadow-2xl shadow-indigo-100/70 sm:p-8">
    <h2 class="mb-5 text-3xl font-black text-slate-950">Cambiar contraseña</h2>

    <?php foreach ($errores as $error): ?>
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?= e($error) ?></div>
    <?php endforeach; ?>

    <?php if ($exito): ?>
        <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?= e($exito) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-4">
            <label class="mb-1.5 block text-sm font-semibold text-slate-800" for="password_actual">Contraseña actual</label>
            <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" type="password" id="password_actual" name="password_actual">
        </div>

        <div class="mb-4">
            <label class="mb-1.5 block text-sm font-semibold text-slate-800" for="password_nuevo">Nueva contraseña</label>
            <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" type="password" id="password_nuevo" name="password_nuevo">
        </div>

        <div class="mb-5">
            <label class="mb-1.5 block text-sm font-semibold text-slate-800" for="confirmar_password">Confirmar nueva contraseña</label>
            <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" type="password" id="confirmar_password" name="confirmar_password">
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
            <button class="rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200" type="submit">Actualizar contraseña</button>
            <a class="rounded-2xl bg-slate-700 px-5 py-3 text-center text-sm font-bold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200" href="perfil.php">Volver al perfil</a>
        </div>
    </form>
</section>

<?php include "includes/footer.php"; ?>
