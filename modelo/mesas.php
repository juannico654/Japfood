<?php
// modelo/mesas.php
require_once '../config/conexion.php';

class Mesa {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->conectar();
    }

    // RF-19: Registrar mesa
    public function registrarMesa($numero_mesa, $capacidad, $ubicacion) {
        try {
            $sql = "INSERT INTO mesas (numero_mesa, capacidad, ubicacion, estado) 
                    VALUES (:numero_mesa, :capacidad, :ubicacion, 'disponible')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':numero_mesa' => $numero_mesa,
                ':capacidad' => $capacidad,
                ':ubicacion' => $ubicacion
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // RF-20: Listar mesas con disponibilidad
    public function listarMesas($filtro = null) {
        try {
            if ($filtro) {
                $sql = "SELECT * FROM mesas WHERE estado = :estado ORDER BY numero_mesa";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':estado' => $filtro]);
            } else {
                $sql = "SELECT * FROM mesas ORDER BY numero_mesa";
                $stmt = $this->pdo->query($sql);
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Listar solo mesas disponibles
    public function listarMesasDisponibles() {
        return $this->listarMesas('disponible');
    }

    // RF-21: Actualizar estado de mesa
    public function actualizarEstado($id_mesa, $estado) {
        try {
            $sql = "UPDATE mesas SET estado = :estado WHERE id_mesa = :id_mesa";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':estado' => $estado,
                ':id_mesa' => $id_mesa
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // RF-22: Eliminar mesa
    public function eliminarMesa($id_mesa) {
        try {
            $sql = "DELETE FROM mesas WHERE id_mesa = :id_mesa";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_mesa' => $id_mesa]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Obtener mesa por ID
    public function obtenerMesaPorId($id_mesa) {
        try {
            $sql = "SELECT * FROM mesas WHERE id_mesa = :id_mesa";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_mesa' => $id_mesa]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // Actualizar mesa completa
    public function actualizarMesa($id_mesa, $numero_mesa, $capacidad, $ubicacion) {
        try {
            $sql = "UPDATE mesas SET numero_mesa = :numero_mesa, capacidad = :capacidad, 
                    ubicacion = :ubicacion WHERE id_mesa = :id_mesa";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':numero_mesa' => $numero_mesa,
                ':capacidad' => $capacidad,
                ':ubicacion' => $ubicacion,
                ':id_mesa' => $id_mesa
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
