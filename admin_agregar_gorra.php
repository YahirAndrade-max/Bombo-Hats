<?php
// admin_agregar_gorra.php
session_start();
include 'conexion.php';
if(!isset($_SESSION['rol'])) header("Location: login.php");

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_gorra'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    
   
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $nombre_original = $_FILES['foto']['name'];
        $ruta_temporal = $_FILES['foto']['tmp_name'];
        
      
        if(!is_dir("uploads")) { 
            mkdir("uploads", 0777, true); 
        }
        
     
        $nombre_imagen_unico = time() . "_" . basename($nombre_original);
        $carpeta_destino = "uploads/" . $nombre_imagen_unico;
        
       
        if (move_uploaded_file($ruta_temporal, $carpeta_destino)) {
           
            $sql = "INSERT INTO catalogo_gorras (nombre, precio, imagen_ruta, stock) VALUES ('$nombre', '$precio', '$nombre_imagen_unico', '$stock')";
            
            if($conn->query($sql)) {
                $mensaje = "✅ Gorra dada de alta e integrada al catálogo correctamente.";
            } else {
                $mensaje = "❌ Error en Base de Datos: " . $conn->error;
            }
        } else {
            $mensaje = "❌ Error crítico: No se pudo mover el archivo a la carpeta 'uploads/'.";
        }
    } else {
        $mensaje = "⚠️ Por favor, selecciona una imagen válida antes de guardar.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Gorra | Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0;}
        .btn-back { background: #27272a; color: white; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; display: inline-block; margin-bottom: 2rem; font-weight:600;}
        .form-box { background: #141417; border: 1px solid #27272a; max-width: 500px; padding: 2.5rem; border-radius: 16px; margin: 0 auto; }
        .form-group { margin-bottom: 1.2rem; display: flex; flex-direction: column; }
        label { font-size: 0.9rem; color: #a1a1aa; margin-bottom: 0.4rem; font-weight:600;}
        input { background: #09090b; border: 1px solid #27272a; color: white; padding: 0.8rem; border-radius: 10px; font-family: inherit;}
        .btn-submit { background: linear-gradient(45deg, #ff2a74, #7928ca); border:none; color:white; padding:1rem; border-radius:10px; font-weight:bold; cursor:pointer; margin-top:1rem;}
    </style>
</head>
<body>
    <a href="panel_admin.php" class="btn-back">⬅️ Panel Central</a>
    <div class="form-box">
        <h2>➕ Agregar Nuevo Modelo de Colección</h2>
        <?php if(!empty($mensaje)) echo "<p style='color:#00ff88; font-weight:bold;'>$mensaje</p>"; ?>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nombre de la Gorra</label>
                <input type="text" name="nombre" required placeholder="Ej: Flexfit Snapback Gold">
            </div>
            <div class="form-group">
                <label>Precio Global ($MXN)</label>
                <input type="number" step="0.01" name="precio" required placeholder="349.99">
            </div>
            <div class="form-group">
                <label>Existencias Iniciales (Stock)</label>
                <input type="number" name="stock" value="10" required>
            </div>
            <div class="form-group">
                <label>Foto de la Gorra</label>
                <input type="file" name="foto" accept="image/*" required>
            </div>
            <button type="submit" name="guardar_gorra" class="btn-submit">Subir e Integrar al Catálogo 🚀</button>
        </form>
    </div>
</body>
</html>