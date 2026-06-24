<?php
// cliente_pedidos.php
session_start();
include 'conexion.php';
if (!isset($_SESSION['id_usuario'])) header("Location: login.php");

$id_usuario = $_SESSION['id_usuario'];

$pedidos_cat = $conn->query("SELECT p.*, g.nombre FROM pedidos_catalogo p JOIN catalogo_gorras g ON p.id_gorra = g.id WHERE p.id_usuario = '$id_usuario' ORDER BY p.fecha DESC");


$pedidos_pers = $conn->query("SELECT p.* FROM pedidos_personalizados p WHERE p.id_usuario = '$id_usuario' ORDER BY p.fecha DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seguimiento | Bombolombo</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0;}
        .btn-back { background:#27272a; color:white; text-decoration:none; padding:0.6rem 1.2rem; border-radius:8px; display:inline-block; margin-bottom:2rem; font-weight:600;}
        .box { background: #141417; border: 1px solid #27272a; border-radius: 16px; padding: 2rem; margin-bottom: 2rem; }
        table { width:100%; border-collapse:collapse; margin-top:1rem;}
        th { text-align:left; color:#a1a1aa; padding:0.8rem; border-bottom:2px solid #27272a;}
        td { padding:1rem 0.8rem; border-bottom:1px solid #1f1f23;}
        .badge { background:#ffaa00; color:#09090b; padding:0.3rem 0.6rem; border-radius:6px; font-weight:bold; font-size:0.8rem; text-transform:uppercase;}
        .badge-Enviado, .badge-Terminado { background:#00ff88; }
        .badge-Cancelado, .badge-Rechazado { background:#ff4444; color:white; }
    </style>
</head>
<body>
    <a href="panel_cliente.php" class="btn-back">⬅️ Volver a mi Dashboard</a>
    <h1>📦 Estado y Rastreo de Órdenes</h1>
    
    <div class="box">
        <h3>🧢 Compras de Catálogo (Vía Carrito)</h3>
        <table>
            <thead><tr><th>Modelo</th><th>Talla</th><th>Cant.</th><th>Total</th><th>Estatus del Envío</th></tr></thead>
            <tbody>
                <?php while($c = $pedidos_cat->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo $c['nombre']; ?></strong></td>
                    <td><?php echo $c['talla']; ?></td>
                    <td><?php echo $c['cantidad']; ?></td>
                    <td style="color:#00ff88;">$<?php echo number_format($c['precio_total'], 2); ?></td>
                    <td><span class="badge badge-<?php echo str_replace(' ', '', $c['estado']); ?>"><?php echo $c['estado']; ?></span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="box">
        <h3>🎨 Diseños Custom Enviados</h3>
        <table>
            <thead><tr><th>Configuración Base</th><th>Talla</th><th>Precio</th><th>Estado de Confección</th></tr></thead>
            <tbody>
                <?php while($p = $pedidos_pers->fetch_assoc()): ?>
                <tr>
                    <td>Gorra color <strong><?php echo $p['color_gorra']; ?></strong></td>
                    <td><?php echo $p['talla']; ?></td>
                    <td style="color:#00ff88;">$<?php echo number_format($p['precio_fijo'], 2); ?></td>
                    <td><span class="badge badge-<?php echo str_replace(' ', '', $p['estado']); ?>"><?php echo $p['estado']; ?></span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>