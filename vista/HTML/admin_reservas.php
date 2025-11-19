<?php
// vista/admin_reservas.php
session_start();
require_once '../modelo/reserva.php';

// Verificar que sea administrador (ajusta según tu lógica de autenticación)
// if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] !== 'admin') {
//     header('Location: ../HTML/index.php');
//     exit();
// }

$reservaModelo = new Reserva();
$filtro = $_GET['filtro'] ?? null;
$reservas = $reservaModelo->consultarReservas($filtro);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Reservas - JapanFood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cinzel', serif; }
        .badge-pendiente { background-color: #ffc107; }
        .badge-confirmada { background-color: #28a745; }
        .badge-cancelada { background-color: #dc3545; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid">
            <h1 class="text-danger" style="font-size:40px;">JapanFood - Admin</h1>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-danger" href="admin_mesas.php">Gestionar Mesas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="../HTML/index.php">Salir</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Administración de Reservas</h2>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <h5>Filtrar por estado:</h5>
                <div class="btn-group" role="group">
                    <a href="admin_reservas.php" class="btn btn-outline-primary <?= !$filtro ? 'active' : '' ?>">Todas</a>
                    <a href="admin_reservas.php?filtro=pendiente" class="btn btn-outline-warning <?= $filtro === 'pendiente' ? 'active' : '' ?>">Pendientes</a>
                    <a href="admin_reservas.php?filtro=confirmada" class="btn btn-outline-success <?= $filtro === 'confirmada' ? 'active' : '' ?>">Confirmadas</a>
                    <a href="admin_reservas.php?filtro=cancelada" class="btn btn-outline-danger <?= $filtro === 'cancelada' ? 'active' : '' ?>">Canceladas</a>
                </div>
            </div>
        </div>

        <!-- Tabla de Reservas -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Personas</th>
                                <th>Mesa</th>
                                <th>Estado</th>
                                <th>Contacto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reservas)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">No hay reservas registradas</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reservas as $reserva): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($reserva['Id_Reserva']) ?></td>
                                        <td><?= htmlspecialchars($reserva['nombre']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($reserva['Fecha'])) ?></td>
                                        <td><?= date('H:i', strtotime($reserva['Hora'])) ?></td>
                                        <td><?= htmlspecialchars($reserva['Cantidad_Personas']) ?></td>
                                        <td>Mesa <?= htmlspecialchars($reserva['numero_mesa']) ?? 'N/A' ?> (<?= htmlspecialchars($reserva['capacidad']) ?? 'N/A' ?> pers.)</td>
                                        <td>
                                            <span class="badge badge-<?= $reserva['estado'] ?>">
                                                <?= ucfirst($reserva['estado']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small>
                                                Tel: <?= htmlspecialchars($reserva['telefono']) ?><br>
                                                Email: <?= htmlspecialchars($reserva['email']) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group-vertical btn-group-sm">
                                                <?php if ($reserva['estado'] === 'pendiente'): ?>
                                                    <button class="btn btn-success" onclick="cambiarEstado(<?= $reserva['Id_Reserva'] ?>, 'confirmada')">
                                                        Confirmar
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <?php if ($reserva['estado'] !== 'cancelada'): ?>
                                                    <button class="btn btn-danger" onclick="cambiarEstado(<?= $reserva['Id_Reserva'] ?>, 'cancelada')">
                                                        Cancelar
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <button class="btn btn-info" onclick="verDetalles(<?= $reserva['Id_Reserva'] ?>)">
                                                    Ver Detalles
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cambiarEstado(idReserva, nuevoEstado) {
            if (confirm(`¿Está seguro de ${nuevoEstado === 'confirmada' ? 'confirmar' : 'cancelar'} esta reserva?`)) {
                fetch('../controlador/actualizar_estado_reserva.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id_reserva=${idReserva}&estado=${nuevoEstado}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Estado actualizado correctamente');
                        location.reload();
                    } else {
                        alert('Error al actualizar el estado');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud');
                });
            }
        }

        function verDetalles(idReserva) {
            window.location.href = `detalle_reserva.php?id=${idReserva}`;
        }
    </script>
</body>
</html>
