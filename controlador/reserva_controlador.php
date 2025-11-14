<?php
require_once "../modelo/reservas.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_mesa = $_POST["id_mesa"];
    $fecha = $_POST["fecha"];
    $personas = $_POST["personas"];
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $preferencias = $_POST["preferencias"];

    $reserva = new Reserva();
    $reserva->agregarReserva($id_mesa, $fecha, $personas, $nombre, $telefono, $email, $preferencias);

    header("Location: ../HTML/reservas.html?ok=1");
    exit();
}
?>
