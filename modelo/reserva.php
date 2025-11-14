<?php
require_once "../config/conexion.php";

class Reserva {

    public function agregarReserva($id_mesa, $fecha, $personas, $nombre, $telefono, $email, $preferencias) {
        $conexion = Database::connect();

        $sql = "INSERT INTO reservas(id_mesa, fecha, personas, nombre, telefono, email, preferencias)
                VALUES (?,?,?,?,?,?,?)";

        $stmt = $conexion->prepare($sql);
        return $stmt->execute([$id_mesa, $fecha, $personas, $nombre, $telefono, $email, $preferencias]);
    }

    public function listarReservas() {
        $conexion = Database::connect();
        $sql = "SELECT reservas.*, mesas.numero_mesa 
                FROM reservas
                INNER JOIN mesas ON reservas.id_mesa = mesas.id";
        return $conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
