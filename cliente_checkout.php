<?php
// cliente_checkout.php
session_start();
include 'conexion.php';
if (!isset($_SESSION['rol'])) header("Location: login.php");

$error_stock = "";
$gran_total = 0;


if (isset($_GET['eliminar'])) {
    $llave_eliminar = $_GET['eliminar'];
    if (isset($_SESSION['carrito'][$llave_eliminar])) {
        unset($_SESSION['carrito'][$llave_eliminar]);
    }
    header("Location: cliente_checkout.php"); // Recarga limpia
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pagar_carrito'])) {
    if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
        $id_usuario = $_SESSION['id_usuario'];
        
        
        $direccion_completa = $_POST['direccion'] . " - Ciudad: " . $_POST['ciudad'];
        $metodo_pago = $_POST['metodo_pago'];
        
        
        $num_tarjeta = !empty($_POST['num_tarjeta']) ? substr($_POST['num_tarjeta'], -4) : NULL;
        
        $conn->begin_transaction(); // Transacción SQL segura
        
        try {
            foreach ($_SESSION['carrito'] as $llave => $item) {
                $id_g = $item['id_gorra'];
                $talla = $item['talla'];
                $cant = $item['cantidad'];
                
             
                $gorra = $conn->query("SELECT * FROM catalogo_gorras WHERE id = '$id_g'")->fetch_assoc();
                
                if ($gorra['stock'] >= $cant) {
                    $total_item = $gorra['precio'] * $cant;
                    
                
                    $conn->query("UPDATE catalogo_gorras SET stock = stock - $cant WHERE id = '$id_g'");
                    
                    
                    $sql_insert = "INSERT INTO pedidos_catalogo (id_usuario, id_gorra, talla, cantidad, precio_total, direccion, num_tarjeta, metodo_pago, estado) 
                                   VALUES ('$id_usuario', '$id_g', '$talla', '$cant', '$total_item', '$direccion_completa', '$num_tarjeta', '$metodo_pago', 'Pendiente')";
                    $conn->query($sql_insert);
                } else {
                    throw new Exception("Stock insuficiente para: " . $gorra['nombre']);
                }
            }
            
            $conn->commit();
            unset($_SESSION['carrito']); // Vaciar el carrito de forma segura
            header("Location: cliente_pedidos.php?success=1");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $error_stock = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pasarela Checkout | Bombolombo</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0; display:grid; grid-template-columns: 1fr; gap:2rem;}
        @media(min-width: 900px) { body { grid-template-columns: 1.2fr 1fr; } }
        .card { background: #141417; border: 1px solid #27272a; padding: 2rem; border-radius: 16px; }
        .form-group { margin-bottom: 1rem; display:flex; flex-direction:column;}
        input, select, textarea { background:#09090b; border:1px solid #27272a; color:white; padding:0.7rem; border-radius:8px; font-family:inherit;}
        .total { font-size:1.5rem; color:#00ff88; font-weight:800; text-align:right; margin-top:1rem;}
        .btn-pay { background: linear-gradient(45deg, #00ff88, #0096ff); border:none; padding:1rem; color:#09090b; font-weight:bold; font-size:1.1rem; border-radius:10px; cursor:pointer; width:100%;}
        .btn-delete { background: #ff4444; color: white; text-decoration: none; padding: 0.3rem 0.6rem; border-radius: 6px; font-size: 0.8rem; font-weight: bold;}
        .btn-delete:hover { background: #cc3333; }
    </style>
</head>
<body>
    <div style="grid-column: 1/-1;"><a href="cliente_catalogo.php" style="color:white; text-decoration:none; font-weight:600;">⬅️ Volver al Catálogo</a></div>
    
    <div class="card">
        <h3>📋 Carrito Actual</h3>
        <?php if(!empty($error_stock)) echo "<p style='color:#ff4444; font-weight:bold;'>⚠️ $error_stock</p>"; ?>
        <?php if(isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])): ?>
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #27272a; text-align: left; color: #a1a1aa;">
                        <th style="padding-bottom: 0.5rem;">Producto</th>
                        <th style="padding-bottom: 0.5rem;">Cantidad</th>
                        <th style="padding-bottom: 0.5rem;">Subtotal</th>
                        <th style="padding-bottom: 0.5rem;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($_SESSION['carrito'] as $llave => $item): 
                    $g = $conn->query("SELECT * FROM catalogo_gorras WHERE id='".$item['id_gorra']."'")->fetch_assoc();
                    $sub = $g['precio'] * $item['cantidad'];
                    $gran_total += $sub;
                ?>
                    <tr style="border-bottom:1px solid #27272a;">
                        <td style="padding:1rem 0;"><strong><?php echo $g['nombre']; ?></strong> <br><small style="color:#a1a1aa;">Talla: <?php echo $item['talla']; ?></small></td>
                        <td><?php echo $item['cantidad']; ?> pza(s)</td>
                        <td style="color:#00ff88;">$<?php echo number_format($sub, 2); ?></td>
                        <td><a href="cliente_checkout.php?eliminar=<?php echo $llave; ?>" class="btn-delete" onclick="return confirm('¿Quitar del carrito?')">🗑️ Eliminar</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total">Total a Pagar: $<?php echo number_format($gran_total, 2); ?> MXN</div>
        <?php else: ?>
            <p style="color:#a1a1aa; text-align: center; padding: 2rem 0;">Tu carrito está totalmente vacío.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>💳 Datos de Envío y Pasarela Bancaria</h3>
        <form action="" method="POST">
            <div class="form-group"><label>Dirección (Calle, Número, Colonia)</label><input type="text" name="direccion" required placeholder="Ej: Av. Reforma #123"></div>
            <div class="form-group"><label>Ciudad / Estado</label><input type="text" name="ciudad" required placeholder="Ej: Veracruz"></div>
            <div class="form-group">
                <label>Forma de Liquidación</label>
                <select name="metodo_pago" id="sel_pago" onchange="checkPago()">
                    <option value="Tarjeta">Tarjeta de Débito / Crédito</option>
                    <option value="OXXO">Efectivo Comercial</option>
                </select>
            </div>
            <div id="panel_banco">
                <div class="form-group"><label>Número de Tarjeta</label><input type="text" name="num_tarjeta" placeholder="1234 5678 9012 3456"></div>
            </div>
            <button type="submit" name="pagar_carrito" class="btn-pay" <?php echo (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) ? 'disabled':''; ?>>Liquidar Orden de Compra ⚡</button>
        </form>
    </div>
    <script>
        function checkPago(){
            var v = document.getElementById("sel_pago").value;
            document.getElementById("panel_banco").style.display = (v === "Tarjeta") ? "block":"none";
        }
    </script>
</body>
</html>