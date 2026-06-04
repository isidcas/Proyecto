<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
use App\Models\Usuario;

$error = ''; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($nombre) && !empty($email) && !empty($password)) {
        if (strlen($password) < 6) {
            $error = "La contraseña debe tener al menos 6 caracteres.";
        } elseif (Usuario::buscarPorEmail($email)) {
            $error = "El correo electrónico ya está registrado.";
        } else {
            if (Usuario::registrar($nombre, $email, $password)) {
                $success = "¡Registro completado! Ya puedes iniciar sesión.";
            } else {
                $error = "Error interno al registrar el usuario.";
            }
        }
    } else { $error = "Rellena todos los campos."; }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container"><div class="row justify-content-center"><div class="col-md-4"><div class="card shadow border-0"><div class="card-body p-4">
        <h3 class="text-center mb-4">Crear Cuenta</h3>
        <?php if($error): ?><div class="alert alert-danger py-2"><?= $error ?></div><?php endif; ?>
        <?php if($success): ?><div class="alert alert-success py-2"><?= $success ?></div><?php endif; ?>
        <form action="register.php" method="POST">
            <div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Contraseña</label><input type="password" name="password" class="form-control" required></div>
            <button type="submit" class="btn btn-success w-100 mt-2">Registrarse</button>
        </form>
        <div class="text-center mt-3"><a href="login.php" class="small text-decoration-none">¿Ya tienes cuenta? Inicia sesión</a></div>
    </div></div></div></div></div>
</body>
</html>