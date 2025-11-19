<?php
require_once __DIR__ . '/../../config/conexion.php'; // ajustar ruta

// Obtener mesas
$sql = "SELECT * FROM mesas ORDER BY numero_mesa";
$res = $conn->query($sql);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Listar Mesas</title>
  <style>
    table{border-collapse:collapse;width:100%}
    th,td{padding:8px;border:1px solid #ddd}
    .disponible{background:#e6ffed}
    .ocupada{background:#ffdede}
    .inactiva{background:#f0f0f0}
  </style>
</head>
<body>
  <h2>Mesas</h2>
  <a href="registrar_mesas.php">Registrar nueva mesa</a>
  <table>
    <thead>
      <tr><th>ID</th><th>Número</th><th>Capacidad</th><th>Ubicación</th><th>Estado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
        <tr class="<?= htmlspecialchars($row['estado']) ?>">
          <td><?= $row['id_mesa'] ?></td>
          <td><?= $row['numero_mesa'] ?></td>
          <td><?= $row['capacidad'] ?></td>
          <td><?= htmlspecialchars($row['ubicacion']) ?></td>
          <td><?= $row['estado'] ?></td>
          <td>
            <a href="editar_mesa.php?id=<?= $row['id_mesa'] ?>">Editar</a> |
            <a href="actualizar_estado_mesa.php?id=<?= $row['id_mesa'] ?>">Cambiar estado</a> |
            <a href="eliminar_mesa.php?id=<?= $row['id_mesa'] ?>" onclick="return confirm('Eliminar mesa?')">Eliminar</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
