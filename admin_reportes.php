<?php
// admin_reportes.php
session_start();
include 'conexion.php';
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'Admin') header("Location: login.php");

// 1. Ganancias Netas Totales (De Catálogo)
$total_cat = $conn->query("SELECT SUM(precio_total) AS ingresos FROM pedidos_catalogo WHERE estado='Entregado'")->fetch_assoc();
// 2. Ganancias Netas Totales (De Personalizados)
$total_pers = $conn->query("SELECT SUM(precio_fijo) AS ingresos FROM pedidos_personalizados WHERE estado='Terminado'")->fetch_assoc();

$ingresos_globales = $total_cat['ingresos'] + $total_pers['ingresos'];

// 3. Quién vendió qué cosa (Rendimiento de Administradores y Trabajadores al cambiar estatus de catálogo)
$sql_rendimiento = "SELECT u.nombre AS empleado, COUNT(p.id) AS despachados, SUM(p.precio_total) AS volumen 
                    FROM pedidos_catalogo p 
                    JOIN usuarios u ON p.id_atendido_por = u.id 
                    WHERE p.estado='Entregado' GROUP BY p.id_atendido_por";
$rendimiento_empleados = $conn->query($sql_rendimiento);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes Financieros | Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0;}
        .btn-back { background: #27272a; color: white; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; display: inline-block; margin-bottom: 2rem; font-weight:600;}
        .kpi-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
        .kpi-card { background: #141417; border: 1px solid #27272a; padding: 2rem; border-radius: 16px; text-align: center;}
        .kpi-card h4 { margin:0; color:#a1a1aa; font-size:0.9rem; text-transform:uppercase;}
        .kpi-card p { margin: 0.5rem 0 0 0; font-size: 2rem; font-weight: 800; color:#00ff88;}
        .card { background: #141417; border: 1px solid #27272a; padding: 2rem; border-radius: 16px; }
        table { width:100%; border-collapse:collapse; margin-top:1.5rem;}
        th { text-align:left; color:#a1a1aa; padding:1rem; border-bottom:2px solid #27272a;}
        td { padding:1rem; border-bottom:1px solid #1f1f23;}
    </style>
</head>
<body>
    <a href="panel_admin.php" class="btn-back">⬅️ Panel Central</a>
    
    <h1>📈 Reportes de Ventas y Rendimiento Organizacional</h1>

    <div class="kpi-container">
        <div class="kpi-card"><h4>Ventas Catálogo (Corte)</h4><p>$<?php echo number_format($total_cat['ingresos'] ?? 0, 2); ?></p></div>
        <div class="kpi-card"><h4>Ventas Laboratorio Custom</h4><p>$<?php echo number_format($total_pers['ingresos'] ?? 0, 2); ?></p></div>
        <div class="kpi-card" style="border-color:#7928ca;"><h4>Caja Bruta Acumulada</h4><p style="color:#ff2a74;">$<?php echo number_format($ingresos_globales, 2); ?></p></div>
    </div>

    <div class="card">
        <h3>👥 Rendimiento del Personal (Quién despachó qué cosa)</h3>
        <table>
            <thead>
                <tr>
                    <th>Colaborador / Operador</th>
                    <th>Órdenes de Catálogo Entregadas</th>
                    <th>Volumen Financiero Aportado</th>
                </tr>
            </thead>
            <tbody>
                <?php while($emp = $rendimiento_empleados->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo $emp['empleado']; ?></strong></td>
                    <td><?php echo $emp['despachados']; ?> órdenes despachadas con éxito</td>
                    <td style="color:#00ff88; font-weight:bold;">$<?php echo number_format($emp['volumen'], 2); ?> MXN</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>