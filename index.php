<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Twitter </title>
    <link rel="stylesheet" href="estilo_index.css">
</head>
<body>
    <div class="left-section">
        <img src="imagenes/x.png" alt="Twitter Logo">
    </div>
    <div class="right-section">
        <h1>Lo que está pasando ahora</h1>
        <h3>Únete Hoy</h3>
        <h2>Inicia Sesión</h2>
        
        <!-- Ventana modal para mostrar mensaje de error -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <p id="error-message"><?php echo isset($_SESSION['login_error']) ? $_SESSION['login_error'] : ''; ?></p>
            </div>
        </div>

        <form id="loginForm" action="loginController.php" method="post">
            <label for="username">Usuario:</label>
            <input class="button-b" type="text" id="username" name="username" required>

            <label for="password">Contraseña:</label>
            <input class="button-b"type="password" id="password" name="password" required>
            <br>
            <input type="submit" value="Iniciar Sesión">
        </form>
        <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>.</p>
        <h5>Al registrarte, aceptas los Términos de servicio y la Política de privacidad, incluida la política de Uso de Cookies.</h5>
    </div>
    <!-- Ventana modal para mostrar mensaje de error -->
<div id="myModal" class="modal" <?php echo isset($_SESSION['login_error']) ? 'style="display:block;"' : ''; ?>>
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p id="error-message"><?php echo isset($_SESSION['login_error']) ? $_SESSION['login_error'] : ''; ?></p>
    </div>
</div>

</body>
</html>
