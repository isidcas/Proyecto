<?php
session_start();

// SEGURIDAD MÁXIMA: Si no es admin, fuera de aquí
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';
use App\Models\Usuario;
use App\Database\Database;

$usuarioActivo = $_SESSION['usuario'];
$error = '';
$success = '';

// Procesar la acción de eliminar un usuario (Caso de uso exclusivo de Admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'])) {
    $idABorrar = intval($_POST['usuario_id'] ?? 0);

    if ($idABorrar === $usuarioActivo['id']) {
        $error = "No puedes eliminar tu propia cuenta de administrador en activo.";
    } elseif ($idABorrar > 0) {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$idABorrar]);
            
            $_SESSION['admin_success'] = "Usuario eliminado correctamente. Su historial y portfolio asociados se han limpiado en cascada.";
            header("Location: admin.php");
            exit;
        } catch (\Exception $e) {
            $error = "Error al eliminar: " . $e->getMessage();
        }
    }
}

if (isset($_SESSION['admin_success'])) {
    $success = $_SESSION['admin_success'];
    unset($_SESSION['admin_success']);
}

// Obtener todos los usuarios del sistema usando tu modelo
$listaUsuarios = Usuario::obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-danger mb-4"><div class="container">
        <a class="navbar-brand" href="#">🛡️ Panel de Control - Administrador</a>
        <div class="navbar-nav me-auto">
            <a class="nav-link text-white bg-dark rounded px-2 me-2 small" href="index.php">⬅️ Volver al Conversor</a>
        </div>
        <div class="navbar-text text-white me-3">Admin: <strong><?= htmlspecialchars($usuarioActivo['nombre']) ?></strong></div>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
    </div></nav>

    <div class="container">
        
        <?php if($error): ?><div class="alert alert-danger py-2"><?=$error?></div><?php endif; ?>
        <?php if($success): ?><div class="alert alert-success py-2"><?=$success?></div><?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1">Mantenimiento de Usuarios Registrados</h4>
                <p class="text-muted small mb-4">Como administrador puedes auditar el sistema y dar de baja cuentas de alumnos o clientes de pruebas.</p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Email institucional</th>
                                <th>Rol del sistema</th>
                                <th>Fecha Registro</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($listaUsuarios as $u): ?>
                                <tr>
                                    <td><strong>#<?=$u['id']?></strong></td>
                                    <td><?=htmlspecialchars($u['nombre'])?></td>
                                    <td><?=htmlspecialchars($u['email'])?></td>
                                    <td>
                                        <span class="badge <?=$u['rol'] === 'admin' ? 'bg-danger' : 'bg-primary'?>">
                                            <?=$u['rol']?>
                                        </span>
                                    </td>
                                    <td class="small text-muted"><?=$u['created_at']?></td>
                                    <td class="text-center">
                                        <?php if($u['id'] !== $usuarioActivo['id']): ?>
                                            <form action="admin.php" method="POST" onsubmit="return confirm('¿Estás completamente seguro de borrar a este usuario? Esta acción limpiará todo su historial.');">
                                                <input type="hidden" name="usuario_id" value="<?=$u['id']?>">
                                                <button type="submit" name="eliminar_usuario" class="btn btn-outline-danger btn-sm px-3">Dar de Baja</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">Eres tú (Protegido)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</body>
</html>