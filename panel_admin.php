<?php
// panel_admin.php
session_start();
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'Admin' && $_SESSION['rol'] != 'Trabajador')) {
    header("Location: login.php");
    exit();
}
$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin HQ | Bombolombo Hats</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; margin: 0; display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: #141417; border-right: 1px solid #27272a; padding: 2rem; display: flex; flex-direction: column; gap: 0.8rem; }
        .sidebar h2 { font-weight: 800; font-size: 1.4rem; background: linear-gradient(45deg, #ff2a74, #7928ca); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 2rem; }
        .sidebar a { color: #a1a1aa; text-decoration: none; padding: 0.8rem 1rem; border-radius: 10px; font-weight: 600; transition: all 0.2s; }
        .sidebar a:hover { color: white; background: #27272a; }
        .sidebar a.active { background: linear-gradient(45deg, #ff2a74, #7928ca); color: white; }
        .main-content { flex: 1; padding: 3rem; }
        .card { background: #141417; border: 1px solid #27272a; padding: 2rem; border-radius: 16px; }
        .badge-rol { background: rgba(121, 40, 202, 0.2); color: #7928ca; padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.8rem; font-weight: bold; margin-left: 0.5rem; border: 1px solid #7928ca;}
    </style>
</head>
<body>

    <aside class="sidebar">
        <h2>Control Terminal</h2>
        <span style="font-size:0.9rem; color:#71717a; margin-bottom:1rem;">Operador: <?php echo $_SESSION['nombre']; ?> <span class="badge-rol"><?php echo $rol; ?></span></span>
        
        <a href="admin_pedidos_cat.php">📦 Pedidos Catálogo</a>
        <a href="admin_pedidos_pers.php">🎨 Pedidos Personalizados</a>
        <a href="admin_agregar_gorra.php">➕ Añadir Modelos</a>

        <?php if ($rol == 'Admin'): ?>
            <hr style="border: 1px solid #27272a; width: 100%; margin: 1rem 0;">
            <a href="admin_stock.php">📊 Gestión Stock y Precios</a>
            <a href="admin_usuarios.php">👥 Control de Usuarios</a>
            <a href="admin_reportes.php">📈 Reportes Mensuales</a>
            <a href="admin_historial.php">📜 Historial Global</a>
        <?php endif; ?>

        <a href="login.php" style="margin-top: auto; color: #ff4444;">Cerrar Sesión 🚪</a>
    </aside>

    <main class="main-content">
        <div class="card">
            <h1>¡Hola de nuevo, <?php echo $_SESSION['nombre']; ?>!</h1>
            <p style="color:#a1a1aa; font-size: 1.1rem;">Selecciona un módulo del panel lateral izquierdo para ver información y despachar los encargos de Gorras.</p>
        </div>
    </main>

</body>
</html>