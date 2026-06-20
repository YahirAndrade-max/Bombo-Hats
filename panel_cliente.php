<?php
// panel_cliente.php
session_start();
include 'conexion.php';
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'Cliente') {
    header("Location: login.php");
    exit();
}

$items_carrito = 0;
if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        $items_carrito += $producto['cantidad'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Bombolombo Hats</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background-color: #09090b; color: white; font-family: 'Outfit', sans-serif; margin: 0; padding: 0; }
        header { background: #141417; border-bottom: 1px solid #27272a; padding: 1.5rem 3rem; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.6rem; font-weight: 800; background: linear-gradient(45deg, #ff2a74, #7928ca); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 0; }
        .cart-link { background: linear-gradient(45deg, #00ff88, #0096ff); color: #09090b; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 10px; font-weight: 800; display: flex; align-items: center; gap: 0.4rem;}
        .container { max-width: 1100px; margin: 5rem auto; padding: 0 2rem; text-align: center; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 2rem; margin-top: 3rem; }
        .menu-card { background: #141417; border: 1px solid #27272a; border-radius: 16px; padding: 2.5rem 1.5rem; text-decoration: none; color: white; transition: all 0.3s; display: flex; flex-direction: column; align-items: center;}
        .menu-card:hover { transform: translateY(-5px); border-color: #7928ca; box-shadow: 0 10px 25px rgba(121,40,202,0.15); }
        .icon-circle { font-size: 3rem; margin-bottom: 1rem; }
        .menu-card h3 { font-size: 1.3rem; margin: 0 0 0.5rem 0; font-weight: 600; }
        .menu-card p { color: #a1a1aa; font-size: 0.9rem; margin: 0; }
    </style>
</head>
<body>

    <header>
        <h1>Bombolombo Hats</h1>
        <div style="display:flex; gap:1rem; align-items:center;">
            <a href="cliente_checkout.php" class="cart-link">🛒 Mi Carrito (<?php echo $items_carrito; ?>)</a>
            <a href="login.php" style="color:#ff4444; text-decoration:none; font-weight:600;">Salir 🚪</a>
        </div>
    </header>

    <div class="container">
        <h2>Bienvenido al Estudio de Diseño Custom, <?php echo $_SESSION['nombre']; ?></h2>
        <p style="color:#a1a1aa;">Arma tus pedidos de catálogo o levanta un boceto exclusivo para costura.</p>

        <div class="menu-grid">
            <a href="cliente_catalogo.php" class="menu-card">
                <div class="icon-circle">🧢</div>
                <h3>Explorar Catálogo</h3>
                <p>Elige tu modelo favorito de colección, escoge la talla y llévalo a tu carrito.</p>
            </a>
            <a href="cliente_personalizar.php" class="menu-card">
                <div class="icon-circle">🎨</div>
                <h3>Laboratorio Custom</h3>
                <p>Sube tu foto o logotipo, selecciona el color base por un precio fijo único.</p>
            </a>
            <a href="cliente_pedidos.php" class="menu-card">
                <div class="icon-circle">📦</div>
                <h3>Mis Pedidos y Rastreo</h3>
                <p>Verifica en tiempo real el estatus de tus órdenes enviadas al taller.</p>
            </a>
        </div>
    </div>

</body>
</html>