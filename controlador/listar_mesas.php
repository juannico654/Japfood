<?php
require_once "../modelo/mesas.php";

$mesa = new Mesa();
$data = $mesa->listarMesas();

header("Content-Type: application/json");
echo json_encode($data);
?>
