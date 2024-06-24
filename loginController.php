<?php
require_once 'connect_data.php'; // Incluir el archivo de conexión a MongoDB

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Buscar el usuario en la colección users
    $user = $usersCollection->findOne(['username' => $username, 'password' => $password]);

    if ($user) {
        // Iniciar sesión
        $_SESSION['username'] = $username;
        // Redirigir a tweets.php
        header("Location: tweets.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Usuario o contraseña incorrecta.";
        header("Location: index.php");
        exit();
    }
}
?>

