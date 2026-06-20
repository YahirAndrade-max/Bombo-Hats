<?php
// admin_stock.php
session_start();
include 'conexion.php';
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'Admin') header("Location: login.php");

$mensaje = "";

// 1. LÓGICA PARA ACTUALIZAR PRECIO Y STOCK
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_stock'])) {
    $id = $_POST['id_gorra'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    
    if($conn->query("UPDATE catalogo_gorras SET precio='$precio', stock='$stock' WHERE id='$id'")) {
        $mensaje = "🔄 Cambios guardados en vivo con éxito.";
    }
}

// 2. LÓGICA PARA ELIMINAR LA GORRA DEL CATÁLOGO
if (isset($_GET['eliminar'])) {
    $id_eliminar = $_GET['eliminar'];
    
    // Primero opcionalmente se puede buscar el nombre de la imagen para borrar el archivo físico, 
    // pero para no complicar tu base de datos, hacemos el DELETE directo.
    if($conn->query("DELETE FROM catalogo_gorras WHERE id='$id_eliminar'")) {
        $mensaje = "🗑️ Gorra eliminada del catálogo correctamente.";
    } else {
        $mensaje = "❌ Error al intentar eliminar: " . $conn->error;
    }
}

$gorras = $conn->query("SELECT * FROM catalogo_gorras");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Manejo Stocks | Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0;}
        .btn-back { background: #27272a; color: white; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; display: inline-block; margin-bottom: 2rem; font-weight:600;}
        .card { background: #141417; border: 1px solid #27272a; padding: 2rem; border-radius: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; color: #a1a1aa; padding: 1rem; border-bottom: 2px solid #27272a; }
        td { padding: 1rem; border-bottom: 1px solid #1f1f23; vertical-align: middle; }
        .mini-input { background: #09090b; border: 1px solid #27272a; color: white; padding: 0.5rem; border-radius: 6px; width: 90px; text-align: center; font-family: inherit;}
        
        /* Botones de acción organizados */
        .actions-cell { display: flex; gap: 0.5rem; align-items: center; }
        .btn-save { background: transparent; border: 1px solid #00ff88; color: white; padding: 0.4rem 1rem; border-radius: 6px; cursor: pointer; font-weight:600; transition: 0.2s;}
        .btn-save:hover { background: #00ff88; color: #09090b;}
        .btn-delete { background: transparent; border: 1px solid #ff4444; color: #ff4444; text-decoration: none; padding: 0.4rem 1rem; border-radius: 6px; font-weight:600; font-size: 0.9rem; transition: 0.2s;}
        .btn-delete:hover { background: #ff4444; color: white;}
        
        /* Estilo para las fotos redondas o cuadradas elegantes */
        .img-preview { width: 55px; height: 55px; object-fit: cover; border-radius: 8px; border: 1px solid #27272a; background: #09090b; }
    </style>
</head>
<body>
    <a href="panel_admin.php" class="btn-back">⬅️ Panel Central</a>
    <div class="card">
        <h2>📊 Gestión de Precios, Stock e Inventario Global</h2>
        <?php if(!empty($mensaje)) echo "<p style='color:#00ff88; font-weight:bold;'>$mensaje</p>"; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Modelo</th>
                    <th>Precio de Venta ($)</th>
                    <th>Stock Disponible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $gorras->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    
                    <td>
                        <?php if(!empty($row['imagen_ruta'])): ?>
                            <img src="uploads/<?php echo $row['imagen_ruta']; ?>" class="img-preview" alt="Preview">
                        <?php else: ?>
                            <div class="img-preview" style="display:flex; align-items:center; justify-content:center; font-size:0.7rem; color:#a1a1aa;">Sin foto</div>
                        <?php endif; ?>
                    </td>
                    
                    <td style="font-weight:600;"><?php echo $row['nombre']; ?></td>
                    
                    <form action="" method="POST">
                        <input type="hidden" name="id_gorra" value="<?php echo $row['id']; ?>">
                        <td>$ <input type="number" step="0.01" name="precio" class="mini-input" value="<?php echo $row['precio']; ?>"></td>
                        <td><input type="number" name="stock" class="mini-input" value="<?php echo $row['stock']; ?>"></td>
                        
                        <td class="actions-cell">
                            <button type="submit" name="update_stock" class="btn-save">Guardar 💾</button>
                            
                            <a href="admin_stock.php?eliminar=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de que deseas borrar permanentemente este modelo del catálogo?')">🗑️ Eliminar</a>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>