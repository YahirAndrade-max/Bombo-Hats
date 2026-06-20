<?php
// cliente_catalogo.php
session_start();
include 'conexion.php';
if (!isset($_SESSION['rol'])) header("Location: login.php");

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $id_gorra = $_POST['id_gorra'];
    $talla = $_POST['talla'];
    
    if(!isset($_SESSION['carrito'])) $_SESSION['carrito'] = array();
    
    // Llave compuesta única por ID y Talla para que no se sobreescriban
    $llave_carrito = $id_gorra . "_" . $talla;
    
    if(isset($_SESSION['carrito'][$llave_carrito])) {
        $_SESSION['carrito'][$llave_carrito]['cantidad']++;
    } else {
        $_SESSION['carrito'][$llave_carrito] = array(
            'id_gorra' => $id_gorra,
            'talla' => $talla,
            'cantidad' => 1
        );
    }
    $mensaje = "🛒 Gorra integrada a tu carrito.";
}

$productos = $conn->query("SELECT * FROM catalogo_gorras");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo | Bombolombo</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0;}
        .header-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;}
        .btn-back { background:#27272a; color:white; text-decoration:none; padding:0.6rem 1.2rem; border-radius:8px; font-weight:600;}
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 2rem; }
        .card-prod { background: #141417; border: 1px solid #27272a; border-radius: 16px; padding: 1.5rem; text-align: center; }
        .prod-img { width: 100%; height: 180px; object-fit: cover; border-radius: 10px; margin-bottom: 1rem; }
        .price { color: #00ff88; font-size: 1.3rem; font-weight: 800; margin: 0.5rem 0; }
        select { background: #09090b; border: 1px solid #27272a; color: white; padding: 0.5rem; border-radius: 6px; width: 100%; margin-bottom: 1rem; font-family: inherit;}
        .btn-buy { background: linear-gradient(45deg, #ff2a74, #7928ca); border:none; color:white; width:100%; padding:0.7rem; border-radius:8px; font-weight:bold; cursor:pointer;}
        .btn-buy:disabled { background: #27272a; color: #71717a; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="header-bar">
        <a href="panel_cliente.php" class="btn-back">⬅️ Volver al Panel</a>
        <a href="cliente_checkout.php" style="color:#00ff88; text-decoration:none; font-weight:800;">Ver Mi Carrito 🛒</a>
    </div>
    <h2>🧢 Colecciones de Gorras Oficiales</h2>
    <?php if(!empty($mensaje)) echo "<p style='color:#00ff88; font-weight:bold;'>$mensaje</p>"; ?>
    
    <div class="grid">
        <?php while($row = $productos->fetch_assoc()): ?>
        <div class="card-prod">
            <img src="uploads/<?php echo $row['imagen_ruta']; ?>" class="prod-img" onerror="this.src='https://placehold.co/240x180?text=Hats';">
            <h3><?php echo $row['nombre']; ?></h3>
            <p class="price">$<?php echo number_format($row['precio'], 2); ?> MXN</p>
            <p style="font-size:0.85rem; color:#a1a1aa; margin-bottom:1rem;">Stock: <?php echo $row['stock']; ?> unidades</p>
            
            <form action="" method="POST">
                <input type="hidden" name="id_gorra" value="<?php echo $row['id']; ?>">
                <label style="font-size:0.8rem; color:#a1a1aa; display:block; text-align:left; margin-bottom:0.2rem;">Seleccionar Talla:</label>
                <select name="talla">
                    <option value="S">Chica (S)</option>
                    <option value="M" selected>Mediana (M)</option>
                    <option value="L">Grande (L)</option>
                </select>
                <button type="submit" name="add_to_cart" class="btn-buy" <?php echo ($row['stock'] <= 0) ? 'disabled' : ''; ?>>
                    <?php echo ($row['stock'] <= 0) ? 'Agotado' : 'Añadir al Carrito 🛒'; ?>
                </button>
            </form>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>