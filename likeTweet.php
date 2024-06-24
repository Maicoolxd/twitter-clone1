<?php
session_start();

require_once 'connect_data.php'; // Asegúrate de incluir correctamente este archivo

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tweet_id'])) {
    $tweet_id = $_POST['tweet_id'];

    // Buscar el tweet por su _id y actualizar los likes
    $updateResult = $tweetsCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectID($tweet_id)],
        ['$inc' => ['likes' => 1]]
    );

    if ($updateResult->getModifiedCount() === 1) {
        // Redirigir de vuelta a tweets.php después de dar like
        header("Location: tweets.php");
        exit();
    } else {
        echo "Error al dar like al tweet.";
    }
} else {
    header("Location: index.php"); // Redirigir si no se reciben datos correctos
    exit();
}
?>
