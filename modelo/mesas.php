<?php
require_once "../config/conexion.php";

class Mesa {

    public function listarMesas() {
        $conexion = Database::connect();
        $sql = "SELECT * FROM mesas";
        return $conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
