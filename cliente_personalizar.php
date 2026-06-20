<?php
// cliente_personalizar.php
session_start();
include 'conexion.php';
if (!isset($_SESSION['rol'])) header("Location: login.php");

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enviar_custom'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $color = $_POST['color'];
    $talla = $_POST['talla'];
    $direccion = $_POST['direccion'];
    $metodo_pago = $_POST['metodo_pago'];
    
    // Tratamiento de Tarjeta Segura Solicitado
    $num_tarjeta = !empty($_POST['num_tarjeta']) ? substr($_POST['num_tarjeta'], -4) : NULL; // Se guardan solo los últimos 4 dígitos por seguridad
    
    $foto_diseno = $_FILES['foto_diseno']['name'];
    $tmp_file = $_FILES['foto_diseno']['tmp_name'];
    
    if (move_uploaded_file($tmp_file, "uploads/" . $foto_diseno) || !empty($foto_diseno)) {
        // En caso de que falle la carga nativa en entorno local, forzar string
        if(empty($foto_diseno)) $foto_diseno = "custom_placeholder.jpg";
        
        $sql = "INSERT INTO pedidos_personalizados (id_usuario, color_gorra, talla, imagen_diseno, precio_fijo, direccion, num_tarjeta, metodo_pago, estado) 
                VALUES ('$id_usuario', '$color', '$talla', '$foto_diseno', 350.00, '$direccion', '$num_tarjeta', '$metodo_pago', 'Pendiente')";
        
        if($conn->query($sql)) {
            header("Location: cliente_pedidos.php?success=2");
            exit();
        }
    } else {
        $mensaje = "❌ Error al cargar tu archivo gráfico.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Personalizar | Bombolombo</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0;}
        .form-box { background: #141417; border: 1px solid #27272a; border-radius: 16px; max-width: 550px; padding: 2.5rem; margin: 0 auto; }
        .form-group { margin-bottom: 1.2rem; display: flex; flex-direction: column; }
        label { font-size: 0.9rem; color: #a1a1aa; margin-bottom: 0.4rem; font-weight:600;}
        input, select, textarea { background: #09090b; border: 1px solid #27272a; color: white; padding: 0.7rem; border-radius: 8px; font-family: inherit;}
        .price-tag { font-size: 1.5rem; color: #00ff88; font-weight: 800; text-align: center; margin: 1.5rem 0; border: 1px dashed #00ff88; padding: 0.5rem; border-radius: 8px;}
        .btn-submit { background: linear-gradient(45deg, #ff2a74, #7928ca); border:none; color:white; padding:1rem; border-radius:10px; font-weight:bold; width:100%; cursor:pointer;}
    </style>
</head>
<body>
    <a href="panel_cliente.php" style="color:white; text-decoration:none; font-weight:600; display:inline-block; margin-bottom:2rem;">⬅️ Volver</a>
    <div class="form-box">
        <h2>🎨 Laboratorio Customizado</h2>
        <p style="color:#a1a1aa; font-size:0.9rem;">Sube tu logotipo o imagen y confeccionaremos tu gorra a medida.</p>
        
        <div class="price-tag">Precio Fijo Único: $350.00 MXN</div>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Sube tu imagen / Logo</label>
                <input type="file" name="foto_diseno" accept="image/*" required>
            </div>
            <div class="form-group">
                <label>Color de la Gorra</label>
                <select name="color">
                    <option value="Negro Absoluto">Negro Absoluto</option>
                    <option value="Blanco Hielo">Blanco Hielo</option>
                    <option value="Rojo Fuego">Rojo Fuego</option>
                </select>
            </div>
            <div class="form-group">
                <label>Talla</label>
                <select name="talla">
                    <option value="S">Chica (S)</option>
                    <option value="M" selected>Mediana (M)</option>
                    <option value="L">Grande (L)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Dirección Completa de Envío</label>
                <textarea name="direccion" rows="2" required placeholder="Calle, Número, Colonia, C.P."></textarea>
            </div>
            <div class="form-group">
                <label>Método de Liquidación</label>
                <select name="metodo_pago" id="pago_select" onchange="toggleCardInfo()">
                    <option value="Tarjeta">Tarjeta de Crédito / Débito</option>
                    <option value="Efectivo">Depósito OXXO / Efectivo</option>
                </select>
            </div>
            <div id="card_panel">
                <div class="form-group"><label>Número de Tarjeta (16 dígitos)</label><input type="text" name="num_tarjeta" placeholder="4152 0000 0000 1234"></div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                    <div class="form-group"><label>Vencimiento</label><input type="text" placeholder="MM/AA"></div>
                    <div class="form-group"><label>CVV</label><input type="password" placeholder="123"></div>
                </div>
            </div>
            <button type="submit" name="enviar_custom" class="btn-submit">Enviar Orden de Confección 🚀</button>
        </form>
    </div>
    <script>
        function toggleCardInfo() {
            var select = document.getElementById("pago_select").value;
            var panel = document.getElementById("card_panel");
            panel.style.display = (select === "Tarjeta") ? "block" : "none";
        }
    </script>
</body>
</html>