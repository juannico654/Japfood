<?php
require_once "../modelo/mesas.php";

$mesa = new Mesa();
$mesas = $mesa->listarMesas();

echo json_encode($mesas);
