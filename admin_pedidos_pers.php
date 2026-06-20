<?php
// admin_pedidos_pers.php
session_start();
include 'conexion.php';
if(!isset($_SESSION['rol'])) header("Location: login.php");

$id_operador = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cambiar_estado_pers'])) {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['nuevo_estado'];
    
    $sql = "UPDATE pedidos_personalizados SET estado = '$nuevo_estado', id_atendido_por = '$id_operador' WHERE id = '$id_pedido'";
    $conn->query($sql);
}

$sql_pers = "SELECT p.*, u.nombre AS cliente_nombre FROM pedidos_personalizados p 
             JOIN usuarios u ON p.id_usuario = u.id 
             WHERE p.estado != 'Terminado' AND p.estado != 'Rechazado' ORDER BY p.fecha ASC";
$resultado = $conn->query($sql_pers);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos Personalizados | Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0;}
        .btn-back { background: #27272a; color: white; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; display: inline-block; margin-bottom: 2rem; font-weight:600;}
        .card { background: #141417; border: 1px solid #27272a; padding: 2rem; border-radius: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        th { text-align: left; color: #a1a1aa; padding: 1rem; border-bottom: 2px solid #27272a; }
        td { padding: 1rem; border-bottom: 1px solid #1f1f23; }
        select { background: #09090b; border: 1px solid #27272a; color: white; padding: 0.5rem; border-radius: 6px; font-family: inherit;}
        .btn-save { background: #ff2a74; border: none; color: white; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight:600; font-family:inherit;}
    </style>
</head>
<body>
    <a href="panel_admin.php" class="btn-back">⬅️ Volver a Panel Central</a>
    <div class="card">
        <h2>🎨 Panel de Confección Custom (Pedidos Personalizados)</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Boceto/Foto</th>
                    <th>Especificación Técnica</th>
                    <th>Talla</th>
                    <th>Precio Fijo</th>
                    <th>Estatus</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><strong><?php echo $row['cliente_nombre']; ?></strong></td>
                    <td>
                        <img src="uploads/<?php echo $row['imagen_diseno']; ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" onerror="this.src='https://placehold.co/60?text=Custom';">
                    </td>
                    <td>Color de gorra: <span style="color:#00ff88;"><?php echo $row['color_gorra']; ?></span></td>
                    <td><span style="background:#27272a; padding:0.2rem 0.5rem; border-radius:4px; font-weight:bold;"><?php echo $row['talla']; ?></span></td>
                    <td style="color:#00ff88; font-weight:bold;">$<?php echo number_format($row['precio_fijo'], 2); ?></td>
                    <td><span style="color:#7928ca; font-weight:bold;"><?php echo $row['estado']; ?></span></td>
                    <td>
                        <form action="" method="POST" style="display:flex; gap:0.5rem;">
                            <input type="hidden" name="id_pedido" value="<?php echo $row['id']; ?>">
                            <select name="nuevo_estado">
                                <option value="Pendiente" <?php if($row['estado']=='Pendiente') echo 'selected'; ?>>Pendiente</option>
                                <option value="En Proceso" <?php if($row['estado']=='En Proceso') echo 'selected'; ?>>En Proceso</option>
                                <option value="Terminado" <?php if($row['estado']=='Terminado') echo 'selected'; ?>>Terminado</option>
                                <option value="Rechazado" <?php if($row['estado']=='Rechazado') echo 'selected'; ?>>Rechazado</option>
                            </select>
                            <button type="submit" name="cambiar_estado_pers" class="btn-save">Actualizar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>