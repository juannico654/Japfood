<?php
// controlador/listar_mesas.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    // Verificar que el archivo del modelo existe
    $modelo_path = __DIR__ . '/../modelo/mesas.php';
    if (!file_exists($modelo_path)) {
        throw new Exception("No se encuentra el archivo modelo/mesas.php");
    }
    
    require_once $modelo_path;
    
    // Crear instancia
    $mesaModelo = new Mesa();
    
    // Obtener mesas
    $solo_disponibles = isset($_GET['disponibles']) && $_GET['disponibles'] == 'true';
    $mesas = $mesaModelo->listarMesas($solo_disponibles);
    
    // Enviar respuesta
    echo json_encode($mesas);
    
} catch (Exception $e) {
    // Log del error
    error_log("ERROR en listar_mesas.php: " . $e->getMessage());
    
    // Respuesta de error
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>
