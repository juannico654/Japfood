<?php
// controlador/reserva_controlador.php
session_start();
require_once '../modelo/reserva.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar datos recibidos
    $fecha_hora = $_POST['fecha'] ?? '';
    $personas = $_POST['personas'] ?? 0;
    $nombre = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';
    $preferencias = $_POST['preferencias'] ?? '';
    $id_mesa = $_POST['id_mesa'] ?? 0;

    // Validaciones básicas
    if (empty($fecha_hora) || empty($personas) || empty($nombre) || empty($telefono) || empty($email) || empty($id_mesa)) {
        header('Location: ../HTML/reservas.html?error=campos_vacios');
        exit();
    }

    // Separar fecha y hora
    $fecha_hora_obj = new DateTime($fecha_hora);
    $fecha = $fecha_hora_obj->format('Y-m-d');
    $hora = $fecha_hora_obj->format('H:i:s');

    // Preparar datos para la reserva
    $datos = [
        'fecha' => $fecha,
        'hora' => $hora,
        'cantidad_personas' => $personas,
        'id_mesa' => $id_mesa,
        'nombre' => $nombre,
        'telefono' => $telefono,
        'email' => $email,
        'descripcion' => $preferencias,
        'cliente_id' => $_SESSION['Id_Usuario'] ?? null,
        'sucursal_id' => 1 // Puedes ajustar esto según tu lógica
    ];

    // Crear reserva
    $reservaModelo = new Reserva();
    $resultado = $reservaModelo->crearReserva($datos);

    if ($resultado) {
        header('Location: ../HTML/reservas.html?success=reserva_creada');
    } else {
        header('Location: ../HTML/reservas.html?error=error_crear');
    }
    exit();
} else {
    header('Location: ../HTML/reservas.html');
    exit();
}
?>
