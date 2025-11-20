<?php
// modelo/mesas.php
require_once __DIR__ . '/../config/conexion.php';

class Mesa {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // RF-20: Listar mesas
    public function listarMesas($solo_disponibles = false) {
        try {
            $sql = "SELECT id_mesa as id, numero_mesa, capacidad, ubicacion, estado FROM mesas";
            
            if ($solo_disponibles) {
                $sql .= " WHERE estado = 'disponible'";
            }
            
            $sql .= " ORDER BY numero_mesa ASC";
            
            $stmt = $this->pdo->query($sql);
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $resultado;
        } catch (PDOException $e) {
            error_log("Error al listar mesas: " . $e->getMessage());
            return [];
        }
    }

    // RF-19: Registrar mesa
    public function registrarMesa($numero_mesa, $capacidad, $ubicacion) {
        try {
            $sql = "INSERT INTO mesas (numero_mesa, capacidad, ubicacion, estado) 
                    VALUES (:numero_mesa, :capacidad, :ubicacion, 'disponible')";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':numero_mesa' => $numero_mesa,
                ':capacidad' => $capacidad,
                ':ubicacion' => $ubicacion
            ]);
        } catch (PDOException $e) {
            error_log("Error al registrar mesa: " . $e->getMessage());
            return false;
        }
    }

    // RF-21: Actualizar estado
    public function actualizarEstado($id_mesa, $estado) {
        try {
            $sql = "UPDATE mesas SET estado = :estado WHERE id_mesa = :id_mesa";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':estado' => $estado,
                ':id_mesa' => $id_mesa
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar estado: " . $e->getMessage());
            return false;
        }
    }

    // RF-22: Eliminar mesa
    public function eliminarMesa($id_mesa) {
        try {
            $sql = "DELETE FROM mesas WHERE id_mesa = :id_mesa";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id_mesa' => $id_mesa]);
        } catch (PDOException $e) {
            error_log("Error al eliminar mesa: " . $e->getMessage());
            return false;
        }
    }
}
?>
