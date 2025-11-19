<?php
// controlador/actualizar_estado_reserva.php
header('Content-Type: application/json');
session_start();
require_once '../modelo/reserva.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reserva = $_POST['id_reserva'] ?? 0;
    $estado = $_POST['estado'] ?? '';

    // Validar estado
    $estados_validos = ['pendiente', 'confirmada', 'cancelada'];
    if (!in_array($estado, $estados_validos)) {
        echo json_encode(['success' => false, 'message' => 'Estado inválido']);
        exit();
    }

    $reservaModelo = new Reserva();
    $resultado = $reservaModelo->actualizarEstadoReserva($id_reserva, $estado);

    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>