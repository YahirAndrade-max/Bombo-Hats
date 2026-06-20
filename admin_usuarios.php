<?php
// admin_usuarios.php
session_start();
include 'conexion.php';
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'Admin') header("Location: login.php");

$mensaje = "";
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_usuario'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];
    
    if($conn->query("INSERT INTO usuarios (nombre, correo, password, rol) VALUES ('$nombre', '$correo', '$password', '$rol')")) {
        $mensaje = "👥 Personal/Cliente dado de alta de inmediato.";
    }
}
$usuarios = $conn->query("SELECT * FROM usuarios ORDER BY rol ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios | Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0; display:grid; grid-template-columns: 1fr; gap:2rem;}
        @media(min-width: 900px) { body { grid-template-columns: 1fr 1.5fr; } }
        .btn-back { background: #27272a; color: white; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; display: inline-block; font-weight:600;}
        .card { background: #141417; border: 1px solid #27272a; padding: 2rem; border-radius: 16px; }
        .form-group { margin-bottom: 1rem; display:flex; flex-direction:column;}
        input, select { background:#09090b; border:1px solid #27272a; color:white; padding:0.6rem; border-radius:8px; font-family:inherit;}
        table { width:100%; border-collapse:collapse; }
        th { text-align:left; color:#a1a1aa; padding:0.8rem; border-bottom:2px solid #27272a;}
        td { padding:0.8rem; border-bottom:1px solid #1f1f23;}
    </style>
</head>
<body>
    <div style="grid-column: 1 / -1;">
        <a href="panel_admin.php" class="btn-back">⬅️ Panel Central</a>
        <h1>Control de Personal y Roles de Acceso</h1>
    </div>

    <div class="card">
        <h3>👥 Registrar Nuevo Miembro</h3>
        <?php if(!empty($mensaje)) echo "<p style='color:#00ff88;'>$mensaje</p>"; ?>
        <form action="" method="POST">
            <div class="form-group"><label>Nombre</label><input type="text" name="nombre" required></div>
            <div class="form-group"><label>Correo</label><input type="email" name="correo" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <div class="form-group">
                <label>Rol de Acceso</label>
                <select name="rol">
                    <option value="Cliente">Cliente Estándar</option>
                    <option value="Trabajador">Trabajador (Línea)</option>
                    <option value="Admin">Administrador Supremo</option>
                </select>
            </div>
            <button type="submit" name="crear_usuario" style="background:#7928ca; border:none; padding:0.8rem; color:white; border-radius:8px; font-weight:bold; cursor:pointer; width:100%;">Registrar Cuenta 💾</button>
        </form>
    </div>

    <div class="card">
        <h3>📋 Cuentas en Base de Datos</h3>
        <table>
            <thead><tr><th>Nombre</th><th>Correo</th><th>Rol del Sistema</th></tr></thead>
            <tbody>
                <?php while($u = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo $u['nombre']; ?></strong></td>
                    <td><?php echo $u['correo']; ?></td>
                    <td><span style="color:#ff2a74; font-weight:bold;"><?php echo $u['rol']; ?></span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>