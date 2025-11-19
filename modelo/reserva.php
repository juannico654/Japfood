<?php
// modelo/reserva.php
require_once '../config/conexion.php';

class Reserva {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->conectar();
    }

    // Crear reserva
    public function crearReserva($datos) {
        try {
            $sql = "INSERT INTO reserva (Fecha, Hora, Cantidad_Personas, id_mesa, nombre, 
                    telefono, email, Descripcion, estado, ClienteId_Cliente, SucursalId) 
                    VALUES (:fecha, :hora, :cantidad_personas, :id_mesa, :nombre, 
                    :telefono, :email, :descripcion, 'pendiente', :cliente_id, :sucursal_id)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':fecha' => $datos['fecha'],
                ':hora' => $datos['hora'],
                ':cantidad_personas' => $datos['cantidad_personas'],
                ':id_mesa' => $datos['id_mesa'],
                ':nombre' => $datos['nombre'],
                ':telefono' => $datos['telefono'],
                ':email' => $datos['email'],
                ':descripcion' => $datos['descripcion'],
                ':cliente_id' => $datos['cliente_id'] ?? null,
                ':sucursal_id' => $datos['sucursal_id'] ?? 1
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear reserva: " . $e->getMessage());
            return false;
        }
    }

    // RF-23: Consultar todas las reservas
    public function consultarReservas($filtro = null) {
        try {
            $sql = "SELECT r.*, m.numero_mesa, m.capacidad, m.ubicacion 
                    FROM reserva r 
                    LEFT JOIN mesas m ON r.id_mesa = m.id_mesa";
            
            if ($filtro) {
                $sql .= " WHERE r.estado = :estado";
            }
            
            $sql .= " ORDER BY r.Fecha DESC, r.Hora DESC";
            
            $stmt = $this->pdo->prepare($sql);
            
            if ($filtro) {
                $stmt->execute([':estado' => $filtro]);
            } else {
                $stmt->execute();
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al consultar reservas: " . $e->getMessage());
            return [];
        }
    }

    // Obtener reserva por ID
    public function obtenerReservaPorId($id_reserva) {
        try {
            $sql = "SELECT r.*, m.numero_mesa, m.capacidad, m.ubicacion 
                    FROM reserva r 
                    LEFT JOIN mesas m ON r.id_mesa = m.id_mesa 
                    WHERE r.Id_Reserva = :id_reserva";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_reserva' => $id_reserva]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // RF-24: Actualizar estado de reserva
    public function actualizarEstadoReserva($id_reserva, $estado) {
        try {
            $sql = "UPDATE reserva SET estado = :estado WHERE Id_Reserva = :id_reserva";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':estado' => $estado,
                ':id_reserva' => $id_reserva
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // RF-24: Modificar reserva completa
    public function modificarReserva($id_reserva, $datos) {
        try {
            $sql = "UPDATE reserva SET Fecha = :fecha, Hora = :hora, 
                    Cantidad_Personas = :cantidad_personas, id_mesa = :id_mesa, 
                    nombre = :nombre, telefono = :telefono, email = :email, 
                    Descripcion = :descripcion 
                    WHERE Id_Reserva = :id_reserva";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':fecha' => $datos['fecha'],
                ':hora' => $datos['hora'],
                ':cantidad_personas' => $datos['cantidad_personas'],
                ':id_mesa' => $datos['id_mesa'],
                ':nombre' => $datos['nombre'],
                ':telefono' => $datos['telefono'],
                ':email' => $datos['email'],
                ':descripcion' => $datos['descripcion'],
                ':id_reserva' => $id_reserva
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Cancelar reserva
    public function cancelarReserva($id_reserva) {
        return $this->actualizarEstadoReserva($id_reserva, 'cancelada');
    }

    // Confirmar reserva
    public function confirmarReserva($id_reserva) {
        return $this->actualizarEstadoReserva($id_reserva, 'confirmada');
    }
}
?>
