<?php
require_once 'connect_data.php'; // Incluir el archivo de conexión a MongoDB

session_start();

// Verificar si se ha enviado un formulario de tweet
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $tweet_content = $_POST['tweet_content'];
    $tweet_image = $_FILES['tweet_image'];

    // Subir la imagen al servidor
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($tweet_image['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Verificar si el archivo de imagen es una imagen real o un archivo falso
    if(isset($_POST["submit"])) {
        $check = getimagesize($tweet_image["tmp_name"]);
        if($check !== false) {
            echo "El archivo es una imagen - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "El archivo no es una imagen.";
            $uploadOk = 0;
        }
    }

    // Verificar si el archivo ya existe
    if (file_exists($target_file)) {
        echo "El archivo ya existe.";
        $uploadOk = 0;
    }

    // Verificar el tamaño del archivo
    if ($tweet_image["size"] > 500000) {
        echo "El archivo es demasiado grande.";
        $uploadOk = 0;
    }

    // Permitir ciertos formatos de archivo
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
        $uploadOk = 0;
    }

    // Verificar si $uploadOk es 0 por un error
    if ($uploadOk == 0) {
        echo "Lo siento, su archivo no fue enviado.";
    // si todo está bien, intenta subir el archivo
    } else {
        if (move_uploaded_file($tweet_image["tmp_name"], $target_file)) {
            echo "El archivo ". htmlspecialchars( basename( $tweet_image["name"])). " ha sido subido.";
        } else {
            echo "Lo siento, ha ocurrido un error subiendo el archivo.";
        }
    }


    // Crear un documento de tweet
    $tweetDocument = [
        'username' => $_SESSION['username'],
        'content' => $tweet_content,
        'image' => $target_file
    ];

    // Insertar el tweet en la colección de tweets
    $insertOneResult = $tweetsCollection->insertOne($tweetDocument);

    if ($insertOneResult->getInsertedCount() === 1) {
        // Redirigir de vuelta a tweets.php o a donde desees
        header("Location: tweets.php");
        exit();
    } else {
        echo "Error al publicar el tweet.";
    }
}
?>
