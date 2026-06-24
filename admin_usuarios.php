<?php
// admin_usuarios.php
session_start();
include 'conexion.php';
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'Admin') header("Location: login.php");

$mensaje = "";


if(isset($_GET['eliminar'])) {
    $id_eliminar = $conn->real_escape_string($_GET['eliminar']);
    
    // Evitar que el administrador se elimine a sí mismo por accidente
    if($id_eliminar == $_SESSION['id_usuario']) {
        $mensaje = "❌ No puedes eliminar tu propia cuenta activa.";
    } else {
        if($conn->query("DELETE FROM usuarios WHERE id = '$id_eliminar'")) {
            $mensaje = "🗑️ Usuario eliminado correctamente del sistema.";
        } else {
            $mensaje = "❌ Error al intentar eliminar el registro: " . $conn->error;
        }
    }
}


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
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; padding: 3rem; margin:0; display:grid; grid-template-columns: 1fr; gap:2rem;}
        @media(min-width: 900px) { body { grid-template-columns: 1fr 1.5fr; } }
        .btn-back { background: #27272a; color: white; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; display: inline-block; font-weight:600;}
        .card { background: #141417; border: 1px solid #27272a; padding: 2rem; border-radius: 16px; }
        .form-group { margin-bottom: 1rem; display:flex; flex-direction:column;}
        input, select { background:#09090b; border:1px solid #27272a; color:white; padding:0.6rem; border-radius:8px; font-family:inherit;}
        table { width:100%; border-collapse:collapse; }
        th { text-align:left; color:#a1a1aa; padding:0.8rem; border-bottom:2px solid #27272a;}
        td { padding:0.8rem; border-bottom:1px solid #1f1f23;}
        .btn-delete { color: #ff4444; text-decoration: none; font-weight: bold; padding: 0.2rem 0.5rem; border-radius: 5px; background: rgba(255, 64, 64, 0.1); border: 1px solid rgba(255, 64, 64, 0.2); transition: 0.2s; }
        .btn-delete:hover { background: rgba(255, 64, 64, 0.3); }
    </style>
</head>
<body>
    <div style="grid-column: 1 / -1;">
        <a href="panel_admin.php" class="btn-back">⬅️ Panel Central</a>
        <h1>Control de Personal y Roles de Acceso</h1>
    </div>

    <div class="card">
        <h3>👥 Registrar Nuevo Miembro</h3>
        <?php if(!empty($mensaje)) echo "<p style='color:#00ff88; font-weight:600;'>$mensaje</p>"; ?>
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
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Contraseña</th>
                    <th>Rol del Sistema</th>
                    <th style="text-align: center;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while($u = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($u['nombre']); ?></strong></td>
                    <td><?php echo htmlspecialchars($u['correo']); ?></td>
                    <td><code style="background: #09090b; padding: 0.2rem 0.5rem; border-radius: 6px; color: #a1a1aa; border: 1px solid #27272a; font-family: monospace; font-size: 0.9rem;"><?php echo htmlspecialchars($u['password']); ?></code></td>
                    <td><span style="color:#ff2a74; font-weight:bold;"><?php echo $u['rol']; ?></span></td>
                    <td style="text-align: center;">
                        <a href="admin_usuarios.php?eliminar=<?php echo $u['id']; ?>" class="btn-delete" onclick="return confirmarEliminacion('<?php echo htmlspecialchars($u['nombre']); ?>')">❌ Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmarEliminacion(nombreUsuario) {
            return confirm("¿Estás completamente seguro de que deseas eliminar al usuario '" + nombreUsuario + "' del sistema? Esta acción no se puede deshacer.");
        }
    </script>
</body>
</html>