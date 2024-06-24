<?php
session_start();

require_once 'connect_data.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Procesar el formulario de comentario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tweet_id']) && isset($_POST['comment_content'])) {
    $tweet_id = $_POST['tweet_id'];
    $comment_content = $_POST['comment_content'];
    $comment = [
        'username' => $_SESSION['username'],
        'content' => $comment_content
    ];

    // Actualizar el documento del tweet para agregar el comentario
    $updateResult = $tweetsCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($tweet_id)],
        ['$push' => ['comments' => $comment]]
    );

    if ($updateResult->getModifiedCount() === 1) {
        // Redireccionar de vuelta a tweets.php después de agregar el comentario
        header("Location: tweets.php");
        exit();
    } else {
        echo "Error al agregar el comentario.";
    }
} else {
    echo "Error: No se recibieron datos de comentario válidos.";
}
?>
