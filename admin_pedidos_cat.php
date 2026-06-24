<?php
// admin_pedidos_cat.php
session_start();
include 'conexion.php';
if(!isset($_SESSION['rol'])) header("Location: login.php");

$id_operador = $_SESSION['id_usuario'];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cambiar_estado'])) {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['nuevo_estado'];
    
    $sql = "UPDATE pedidos_catalogo SET estado = '$nuevo_estado', id_atendido_por = '$id_operador' WHERE id = '$id_pedido'";
    $conn->query($sql);
}

$sql_pedidos = "SELECT p.*, g.nombre AS gorra_nombre, u.nombre AS cliente_nombre 
                FROM pedidos_catalogo p 
                JOIN catalogo_gorras g ON p.id_gorra = g.id 
                JOIN usuarios u ON p.id_usuario = u.id 
                WHERE p.estado != 'Entregado' AND p.estado != 'Cancelado' ORDER BY p.fecha ASC";
$resultado = $conn->query($sql_pedidos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos Catálogo | Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0;}
        .btn-back { background: #27272a; color: white; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; display: inline-block; margin-bottom: 2rem;}
        .card { background: #141417; border: 1px solid #27272a; padding: 2rem; border-radius: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        th { text-align: left; color: #a1a1aa; padding: 1rem; border-bottom: 2px solid #27272a; }
        td { padding: 1rem; border-bottom: 1px solid #1f1f23; }
        select { background: #09090b; border: 1px solid #27272a; color: white; padding: 0.5rem; border-radius: 6px; font-family: inherit; }
        .btn-save { background: #7928ca; border: none; color: white; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-family: inherit; font-weight: 600;}
    </style>
</head>
<body>
    <a href="panel_admin.php" class="btn-back">⬅️ Volver a Panel Central</a>
    <div class="card">
        <h2>📦 Pedidos Activos de Catálogo (Línea de Producción)</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Producto Seleccionado</th>
                    <th>Talla</th>
                    <th>Cant.</th>
                    <th>Dirección de Destino</th>
                    <th>Estatus Actual</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><strong><?php echo $row['cliente_nombre']; ?></strong></td>
                    <td style="color:#ff2a74; font-weight:600;"><?php echo $row['gorra_nombre']; ?></td>
                    <td><span style="background:#27272a; padding:0.2rem 0.5rem; border-radius:4px; font-weight:bold;"><?php echo $row['talla']; ?></span></td>
                    <td><?php echo $row['cantidad']; ?></td>
                    <td><?php echo $row['direccion']; ?> <br><small style="color:#71717a; ?></small></td>
                    <td><span style="color:#ffaa00; font-weight:bold;"><?php echo $row['estado']; ?></span></td>
                    <td>
                        <form action="" method="POST" style="display:flex; gap:0.5rem;">
                            <input type="hidden" name="id_pedido" value="<?php echo $row['id']; ?>">
                            <select name="nuevo_estado">
                                <option value="Pendiente" <?php if($row['estado']=='Pendiente') echo 'selected'; ?>>Pendiente</option>
                                <option value="En Proceso" <?php if($row['estado']=='En Proceso') echo 'selected'; ?>>En Proceso</option>
                                <option value="Enviado" <?php if($row['estado']=='Enviado') echo 'selected'; ?>>Enviado</option>
                                <option value="Entregado" <?php if($row['estado']=='Entregado') echo 'selected'; ?>>Entregado</option>
                                <option value="Cancelado" <?php if($row['estado']=='Cancelado') echo 'selected'; ?>>Cancelado</option>
                            </select>
                            <button type="submit" name="cambiar_estado" class="btn-save">Actualizar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>