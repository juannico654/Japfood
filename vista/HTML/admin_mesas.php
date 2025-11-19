<?php
// vista/admin_mesas.php
session_start();
require_once '../modelo/mesas.php';

$mesaModelo = new Mesa();
$mesas = $mesaModelo->listarMesas();

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    if ($accion === 'crear') {
        $resultado = $mesaModelo->registrarMesa(
            $_POST['numero_mesa'],
            $_POST['capacidad'],
            $_POST['ubicacion']
        );
        $mensaje = $resultado ? 'Mesa creada exitosamente' : 'Error al crear mesa';
    } elseif ($accion === 'actualizar_estado') {
        $resultado = $mesaModelo->actualizarEstado($_POST['id_mesa'], $_POST['estado']);
        $mensaje = $resultado ? 'Estado actualizado' : 'Error al actualizar estado';
    } elseif ($accion === 'eliminar') {
        $resultado = $mesaModelo->eliminarMesa($_POST['id_mesa']);
        $mensaje = $resultado ? 'Mesa eliminada' : 'Error al eliminar mesa';
    }
    
    header('Location: admin_mesas.php?msg=' . urlencode($mensaje));
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Mesas - JapanFood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cinzel', serif; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid">
            <h1 class="text-danger" style="font-size:40px;">JapanFood - Admin Mesas</h1>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-danger" href="admin_reservas.php">Ver Reservas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="../HTML/index.php">Salir</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-info alert-dismissible fade show">
                <?= htmlspecialchars($_GET['msg']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5>Registrar Nueva Mesa</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="accion" value="crear">
                            <div class="mb-3">
                                <label class="form-label">Número de Mesa</label>
                                <input type="number" class="form-control" name="numero_mesa" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Capacidad</label>
                                <input type="number" class="form-control" name="capacidad" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ubicación</label>
                                <input type="text" class="form-control" name="ubicacion" required>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Registrar Mesa</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5>Listado de Mesas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Número</th>
                                        <th>Capacidad</th>
                                        <th>Ubicación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mesas as $mesa): ?>
                                        <tr>
                                            <td><?= $mesa['id_mesa'] ?></td>
                                            <td><?= $mesa['numero_mesa'] ?></td>
                                            <td><?= $mesa['capacidad'] ?> personas</td>
                                            <td><?= htmlspecialchars($mesa['ubicacion']) ?></td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="accion" value="actualizar_estado">
                                                    <input type="hidden" name="id_mesa" value="<?= $mesa['id_mesa'] ?>">
                                                    <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                                        <option value="disponible" <?= $mesa['estado'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                                                        <option value="ocupada" <?= $mesa['estado'] === 'ocupada' ? 'selected' : '' ?>>Ocupada</option>
                                                        <option value="inactiva" <?= $mesa['estado'] === 'inactiva' ? 'selected' : '' ?>>Inactiva</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta mesa?');">
                                                    <input type="hidden" name="accion" value="eliminar">
                                                    <input type="hidden" name="id_mesa" value="<?= $mesa['id_mesa'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
