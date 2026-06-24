<?php
// login.php
session_start();
include 'conexion.php';
$error = "";
$exito = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['accion_login'])) {
        $correo = $_POST['correo'];
        $password = $_POST['password'];

  
        $correo = $conn->real_escape_string($correo);
        $password = $conn->real_escape_string($password);

        $sql = "SELECT * FROM usuarios WHERE correo = '$correo' AND password = '$password'";
        $res = $conn->query($sql);

        if ($res && $res->num_rows > 0) {
            $user = $res->fetch_assoc();
            $_SESSION['id_usuario'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol'];

            if ($user['rol'] == 'Admin' || $user['rol'] == 'Trabajador') {
                header("Location: panel_admin.php");
            } else {
                header("Location: panel_cliente.php");
            }
            exit();
        } else {
            $error = "Credenciales incorrectas de acceso.";
        }
    }

   
    if (isset($_POST['accion_registro'])) {
        $nombre = $_POST['reg_nombre'];
        $correo = $_POST['reg_correo'];
        $password = $_POST['reg_password'];
        $rol_por_defecto = 'Cliente'; // Forzado estrictamente por seguridad de la plataforma

        $nombre = $conn->real_escape_string($nombre);
        $correo = $conn->real_escape_string($correo);
        $password = $conn->real_escape_string($password);

     
        $check_correo = "SELECT id FROM usuarios WHERE correo = '$correo'";
        $res_check = $conn->query($check_correo);

        if ($res_check && $res_check->num_rows > 0) {
            $error = "El correo electrónico ya se encuentra registrado.";
        } else {
          
            $sql_insert = "INSERT INTO usuarios (nombre, correo, password, rol) VALUES ('$nombre', '$correo', '$password', '$rol_por_defecto')";
            
            if ($conn->query($sql_insert)) {
                $exito = "¡Cuenta creada con éxito! Ya puedes iniciar sesión.";
            } else {
                $error = "Error al registrar la cuenta en la base de datos: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Sistema | Bombolombo Hats</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap');
        body { background: #09090b; color: white; font-family: 'Outfit', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box;}
        .login-card { background: #141417; border: 1px solid #27272a; padding: 3rem; border-radius: 20px; width: 100%; max-width: 420px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); text-align: center;}
        h2 { font-weight: 800; background: linear-gradient(45deg, #ff2a74, #7928ca); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 2rem; margin-top: 0; letter-spacing: 1px;}
        .form-group { margin-bottom: 1.2rem; text-align: left; display: flex; flex-direction: column; }
        label { font-size: 0.85rem; color: #a1a1aa; margin-bottom: 0.5rem; font-weight: 600;}
        input { background: #09090b; border: 1px solid #27272a; color: white; padding: 0.8rem; border-radius: 10px; font-family: inherit;}
        input:focus { border-color: #7928ca; outline: none; }
        .btn-submit { background: linear-gradient(45deg, #ff2a74, #7928ca); border: none; color: white; padding: 1rem; width: 100%; border-radius: 10px; font-weight: bold; cursor: pointer; font-size: 1rem; margin-top: 0.5rem; transition: transform 0.2s; font-family: inherit;}
        .btn-submit:active { transform: scale(0.98); }
        .error { color: #ff4444; font-size: 0.9rem; margin-bottom: 1.5rem; background: rgba(255, 68, 68, 0.1); padding: 0.8rem; border-radius: 10px; border: 1px solid rgba(255, 68, 68, 0.2); }
        .exito { color: #00e676; font-size: 0.9rem; margin-bottom: 1.5rem; background: rgba(0, 230, 118, 0.1); padding: 0.8rem; border-radius: 10px; border: 1px solid rgba(0, 230, 118, 0.2); }
        
        
        .toggle-link { color: #a1a1aa; font-size: 0.9rem; margin-top: 1.5rem; display: block; cursor: pointer; text-decoration: underline; font-weight: 400; }
        .toggle-link:hover { color: #ff2a74; }
        .form-section { display: none; }
        .form-active { display: block; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>BOMBOLOMBO HATS</h2>
        
        <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if(!empty($exito)) echo "<p class='exito'>$exito</p>"; ?>

        <div id="section-login" class="form-section <?php echo isset($_POST['accion_registro']) ? '' : 'form-active'; ?>">
            <form action="" method="POST">
                <input type="hidden" name="accion_login" value="1">
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="correo" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Ingresar al Sistema ⚡</button>
            </form>
            <span class="toggle-link" onclick="alternarFormulario('registro')">¿No tienes una cuenta? Regístrate aquí</span>
        </div>

        <div id="section-registro" class="form-section <?php echo isset($_POST['accion_registro']) ? 'form-active' : ''; ?>">
            <form action="" method="POST">
                <input type="hidden" name="accion_registro" value="1">
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="reg_nombre" required placeholder="Ej. Juan Pérez">
                </div>
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="reg_correo" required placeholder="ejemplo@correo.com">
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="reg_password" required placeholder="Crea una contraseña segura">
                </div>
                <button type="submit" class="btn-submit">Crear Cuenta Comercial 🛍️</button>
            </form>
            <span class="toggle-link" onclick="alternarFormulario('login')">¿Ya tienes cuenta? Inicia sesión aquí</span>
        </div>
    </div>

    <script>
        function alternarFormulario(pantalla) {
         
            const errores = document.querySelectorAll('.error, .exito');
            errores.forEach(el => el.style.display = 'none');

            if (pantalla === 'registro') {
                document.getElementById('section-login').classList.remove('form-active');
                document.getElementById('section-registro').classList.add('form-active');
            } else {
                document.getElementById('section-registro').classList.remove('form-active');
                document.getElementById('section-login').classList.add('form-active');
            }
        }
    </script>
</body>
</html>