<?php
session_start();
require_once 'connect_data.php'; // Asegúrate de incluir correctamente este archivo

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Verificar que se recibió el ID del tweet a eliminar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tweet_id'])) {
    $tweet_id = $_POST['tweet_id'];

    // Convertir el ID a un objeto ObjectId de MongoDB
    try {
        $tweetObjectId = new MongoDB\BSON\ObjectId($tweet_id);
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error: ID de tweet no válido.";
        exit();
    }

    // Eliminar el tweet de la colección
    $deleteResult = $tweetsCollection->deleteOne(['_id' => $tweetObjectId]);

    if ($deleteResult->getDeletedCount() === 1) {
        // Redireccionar de vuelta a tweets.php después de eliminar el tweet
        header("Location: tweets.php");
        exit();
    } else {
        echo "Error al intentar eliminar el tweet.";
    }
} else {
    echo "Acceso no autorizado.";
}
?>
