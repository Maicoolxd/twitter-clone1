<?php
require_once("connect_data.php");

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $edad = $_POST['edad'];
    $password = $_POST['password'];

    // Crear el documento del usuario
    $userDocument = array(
        "username" => $username,
        "email" => $email,
        "telefono" => $telefono,
        "edad" => $edad,
        "password" => $password
    );

    // Insertar el documento en la colección de usuarios
    $insertOneResult = $usersCollection->insertOne($userDocument);

    // Verificar si la inserción fue exitosa
    if ($insertOneResult->getInsertedCount() === 1) {
        echo "<script>document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('myModal').style.display = 'block';
            });</script>";
    } else {
        echo "<p>Error al registrar el usuario.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" type="text/css" href="estilo_register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="close-icon"><i class="fas fa-times"></i></a>
        <h1>Crea tu cuenta</h1>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono">
            </div>
            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="text" id="edad" name="edad">
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Registrar">
        </form>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Registro Exitoso</p>
            <button onclick="closeModalAndRedirect()">Iniciar</button>
        </div>
    </div>

    <script>
        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        function closeModalAndRedirect() {
            closeModal();
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>
