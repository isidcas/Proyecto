<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';
use App\Services\ConversorService;
use App\Models\Portfolio;

$usuario = $_SESSION['usuario'];
$monedasApi = ConversorService::obtenerMonedasDisponibles();
$divisasLocales = ConversorService::obtenerDivisasLocales();

$resultado_conversion = null;
$error = '';
$success = '';

// PROCESAR FORMULARIO 1: CONVERSIÓN DE DIVISAS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['convertir'])) {
    $amount = floatval($_POST['amount'] ?? 1.0);
    $from = $_POST['from'] ?? 'EUR';
    $to = $_POST['to'] ?? 'USD';

    $resultado = ConversorService::convertirEHistoriar($usuario['id'], $from, $to, $amount);
    if ($resultado !== null) {
        $_SESSION['ultimo_resultado'] = ['amount' => $amount, 'from' => $from, 'to' => $to, 'resultado' => $resultado];
        header("Location: index.php");
        exit;
    } else {
        $error = "Error al realizar la conversión. Revisa las monedas.";
    }
}

// PROCESAR FORMULARIO 2: NUEVO CASO DE USO (Añadir fondos al Portfolio N:M)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_asset'])) {
    $divisa = $_POST['divisa_codigo'] ?? '';
    $cantidad = floatval($_POST['cantidad_poseida'] ?? 0.0);

    if (!empty($divisa) && $cantidad > 0) {
        if (Portfolio::actualizarSaldo($usuario['id'], $divisa, $cantidad)) {
            $_SESSION['mensaje_success'] = "¡Portfolio actualizado! Has añadido fondos con éxito.";
            header("Location: index.php");
            exit;
        } else {
            $error = "No se pudo actualizar el portfolio.";
        }
    } else {
        $error = "Por favor, introduce una cantidad válida mayor que cero.";
    }
}

// Recuperar mensajes de la sesión tras la redirección anti-duplicados
if (isset($_SESSION['ultimo_resultado'])) {
    $resultado_conversion = $_SESSION['ultimo_resultado'];
    unset($_SESSION['ultimo_resultado']);
}
if (isset($_SESSION['mensaje_success'])) {
    $success = $_SESSION['mensaje_success'];
    unset($_SESSION['mensaje_success']);
}

$historial = ConversorService::obtenerHistorial($usuario['id']);
$portfolio = Portfolio::obtenerPorUsuario($usuario['id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Conversor & Portfolio - Proyecto DAW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4"><div class="container">
        <a class="navbar-brand" href="#">💱 Forex 365</a>
        <div class="d-flex align-items-center">
            <div class="navbar-text text-white me-3">
                Bienvenido, <strong><?= htmlspecialchars($usuario['nombre']) ?></strong>
                <?php if($usuario['rol'] === 'admin'): ?>
                    <span class="badge bg-danger ms-1">Admin</span>
                <?php endif; ?>
            </div>
            
            <?php if($usuario['rol'] === 'admin'): ?>
                <a href="admin.php" class="btn btn-warning btn-sm fw-bold me-3">🛡️ Panel Admin</a>
            <?php endif; ?>

            <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
        </div>
    </div></nav>

    <div class="container">
        
        <?php if($error): ?><div class="alert alert-danger shadow-sm py-2"><?= $error ?></div><?php endif; ?>
        <?php if($success): ?><div class="alert alert-success shadow-sm py-2"><?= $success ?></div><?php endif; ?>

        <div class="row g-4">
            
            <div class="col-md-6">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold text-primary mb-3">🔄 Conversor en Tiempo Real</h5>
                        <form action="index.php" method="POST">
                            <div class="mb-3"><label class="form-label fw-semibold">Cantidad a cambiar</label><input type="number" step="0.01" name="amount" class="form-control" value="1.00" required></div>
                            <div class="row mb-3">
                                <div class="col"><label class="form-label small">Origen</label><select name="from" class="form-select"><?php foreach($monedasApi as $c=>$n):?><option value="<?=$c?>"><?=$c?> - <?=$n?></option><?php endforeach;?></select></div>
                                <div class="col"><label class="form-label small">Destino</label><select name="to" class="form-select"><?php foreach($monedasApi as $c=>$n):?><option value="<?=$c?>" <?=$c==='USD'?'selected':''?>><?=$c?> - <?=$n?></option><?php endforeach;?></select></div>
                            </div>
                            <button type="submit" name="convertir" class="btn btn-primary w-100 py-2 fw-semibold">Efectuar Conversión</button>
                        </form>
                        <?php if($resultado_conversion): ?><div class="alert alert-info mt-4 text-center fw-bold fs-5 mb-0"><?= number_format($resultado_conversion['amount'],2)?> <?=$resultado_conversion['from']?> = <?= number_format($resultado_conversion['resultado'],4)?> <?=$resultado_conversion['to']?></div><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold text-success mb-2">💼 Mi Cartera de Activos </h5>
                        <p class="text-muted small mb-3">Evolución de saldos acumulados por el usuario actual en el sistema.</p>
                        
                        <?php if (empty($portfolio)): ?>
                            <div class="alert alert-warning py-2 text-center small">Tu cartera está vacía. ¡Añade fondos abajo!</div>
                        <?php else: ?>
                            <div class="list-group mb-4">
                                <?php foreach($portfolio as $item): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <div><span class="fw-bold"><?=$item['codigo']?></span> - <span class="text-muted small"><?=$item['nombre']?></span></div>
                                        <span class="badge bg-success fs-6 fw-normal"><?=$item['simbolo']?> <?=number_format($item['cantidad_poseida'], 4)?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="bg-light p-3 rounded border">
                            <h6 class="fw-bold mb-2 small text-uppercase text-secondary">➕ Operación: Recargar / Modificar Cartera</h6>
                            <form action="index.php" method="POST" class="row g-2 align-items-end">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Elegir Divisa</label>
                                    <select name="divisa_codigo" class="form-select form-select-sm" required>
                                        <?php foreach($divisasLocales as $dl): ?>
                                            <option value="<?=$dl['codigo']?>"><?=$dl['codigo']?> - <?=$dl['nombre']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Monto a Añadir</label>
                                    <input type="number" step="0.0001" name="cantidad_poseida" class="form-control form-select-sm" placeholder="0.00" required>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" name="guardar_asset" class="btn btn-success btn-sm w-100 fw-semibold">Confirmar Operación en Portfolio</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-2 mb-5"><div class="card-body p-4">
            <h5 class="card-title fw-bold text-dark mb-3">⏱️ Histórico de Auditoría de Cambios</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>Fecha / Hora</th><th>Moneda Origen</th><th>Moneda Destino</th><th>Cantidad Enviada</th><th>Monto Obtenido</th></tr></thead>
                    <tbody>
                        <?php if(empty($historial)): ?>
                            <tr><td colspan="5" class="text-center text-muted small">No hay operaciones registradas.</td></tr>
                        <?php else: ?>
                            <?php foreach($historial as $h): ?>
                                <tr>
                                    <td class="small text-muted"><?=date('d/m/Y H:i', strtotime($h['fecha']))?></td>
                                    <td><span class="badge bg-secondary px-2 py-1"><?=$h['moneda_origen']?></span></td>
                                    <td><span class="badge bg-info text-dark px-2 py-1"><?=$h['moneda_destino']?></span></td>
                                    <td class="fw-semibold"><?=number_format($h['cantidad'],2)?></td>
                                    <td class="text-success fw-bold"><?=number_format($h['resultado'],4)?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div></div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>