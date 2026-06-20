<?php
// admin_historial.php
session_start();
include 'conexion.php';
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'Admin') header("Location: login.php");

$sql_historial = "SELECT p.fecha, p.precio_total, p.estado, g.nombre AS producto, u_cli.nombre AS cliente, u_emp.nombre AS atendido_por 
                  FROM pedidos_catalogo p 
                  JOIN catalogo_gorras g ON p.id_gorra = g.id 
                  JOIN usuarios u_cli ON p.id_usuario = u_cli.id 
                  LEFT JOIN usuarios u_emp ON p.id_atendido_por = u_emp.id 
                  ORDER BY p.fecha DESC";
$historial = $conn->query($sql_historial);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial Global | Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0;}
        .btn-back { background: #27272a; color: white; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; display: inline-block; margin-bottom: 2rem; font-weight:600;}
        .card { background: #141417; border: 1px solid #27272a; padding: 2rem; border-radius: 16px; }
        table { width:100%; border-collapse:collapse; }
        th { text-align:left; color:#a1a1aa; padding:1rem; border-bottom:2px solid #27272a;}
        td { padding:1rem; border-bottom:1px solid #1f1f23;}
    </style>
</head>
<body>
    <a href="panel_admin.php" class="btn-back">⬅️ Panel Central</a>
    <div class="card">
        <h2>📜 Historial Inalterable de Pedidos Procesados</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Producto</th>
                    <th>Cliente</th>
                    <th>Importe</th>
                    <th>Personal que Atendió</th>
                    <th>Estatus Final</th>
                </tr>
            </thead>
            <tbody>
                <?php while($h = $historial->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('d/m/Y H:i', strtotime($h['fecha'])); ?></td>
                    <td style="color:#ff2a74; font-weight:600;"><?php echo $h['producto']; ?></td>
                    <td><?php echo $h['cliente']; ?></td>
                    <td style="color:#00ff88; font-weight:bold;">$<?php echo number_format($h['precio_total'], 2); ?></td>
                    <td><span style="color:#7928ca; font-weight:600;"><?php echo $h['atendido_por'] ?? 'Autodespachado / WEB'; ?></span></td>
                    <td><strong><?php echo $h['estado']; ?></strong></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>