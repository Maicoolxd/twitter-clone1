<?php
session_start();
require_once 'connect_data.php'; 

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Procesar el cierre de sesión si se envió
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Procesar el formulario de publicación de tweets si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tweet_content'])) {
    $tweet_content = $_POST['tweet_content'];

    // Manejo de la imagen adjunta si se proporcionó
    $tweet_image = null;
    if (isset($_FILES['tweet_image']) && $_FILES['tweet_image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['tweet_image']['tmp_name'];
        $name = basename($_FILES['tweet_image']['name']);
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . $name;

        if (move_uploaded_file($tmp_name, $upload_file)) {
            $tweet_image = $name;
        } else {
            echo "Error al subir la imagen.";
        }
    }

    // Crear el documento del tweet
    $tweetDocument = [
        'username' => $_SESSION['username'],
        'content' => $tweet_content,
        'image' => $tweet_image,
        'likes' => 0,
        'comments' => [],
        'timestamp' => new MongoDB\BSON\UTCDateTime()
    ];

    // Insertar el tweet en la colección
    $insertOneResult = $tweetsCollection->insertOne($tweetDocument);

    if ($insertOneResult->getInsertedCount() === 1) {
        // Redireccionar para evitar envíos de formulario repetidos
        header("Location: tweets.php");
        exit();
    } else {
        echo "Error al publicar el tweet.";
    }
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
}

// Obtener todos los tweets de la colección, ordenados por timestamp descendente
$tweets = $tweetsCollection->find([], ['sort' => ['timestamp' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter Clone</title>
    <link rel="stylesheet" href="estilo_tweets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <img src="imagenes/x1.png" alt=" ">
        <a href="tweets.php"><i class="fas fa-home"></i> Inicio</a>
        <br>
        <a href="https://x.com/explore"><i class="fas fa-globe-americas"></i> Explorar</a>
        <br>
        <a href="https://x.com/notifications"><i class="fas fa-bell"></i> Notificaciones</a>
        <br>
        <a href="https://x.com/messages"><i class="fas fa-envelope"></i> Mensajes</a>
        <br>
        <a href="https://x.com/Maicol_x_D/communities/explore"><i class="fas fa-users"></i> Comunidades</a>
        <br>
        <a href="#"><i class="fas fa-ellipsis-h"></i> Más Opciones</a>
        <br>
        <br>
        <br>
        <br>
        <!-- Botón de Cerrar Sesión -->
            <button class="logout-btn" onclick="logout()">
                <i class="fas fa-times"></i> Cerrar Sesión
            </button>

            <!-- Script para manejar el cierre de sesión -->
            <script>
            function logout() {
                window.location.href = "?logout=true";
            }
            </script>

<!-- Asegúrate de tener incluido Font Awesome para los iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    </div>
    
    <div class="container">
            <div class="tweet-form">
                <!-- Formulario de publicación de tweets -->
                <form action="tweets.php" method="post" enctype="multipart/form-data">
                    <textarea name="tweet_content" placeholder="¿Qué estás pensando?"></textarea>
                    <input type="file" name="tweet_image" accept="image/*">
                    <input type="submit" value="Publicar">
                </form>
            </div>


        <div class="tweet-list">
            <?php foreach ($tweets as $tweet): ?>
                <div class="tweet">
                    <div class="tweet-header">
                        <img src="imagenes/usu.png" alt="Avatar">
                        <div class="user-info">
                            <h3><?php echo $tweet['username']; ?></h3>
                            <!-- Puedes agregar otros detalles del usuario como el handle (@usuario) si lo tienes -->
                        </div>
                    </div>                    
                    <div class="tweet-content">
                        <p><?php echo $tweet['content']; ?></p>
                        <?php if (!empty($tweet['image'])): ?>
                            <img src="uploads/<?php echo $tweet['image']; ?>" alt="Imagen adjunta">
                        <?php endif; ?>
                    </div>
                    <div class="tweet-actions">
                        <!-- Botón de Like -->
                        <form action="likeTweet.php" method="post">
                            <input type="hidden" name="tweet_id" value="<?php echo $tweet['_id']; ?>">
                            <button type="submit" class="like-btn">❤️ <?php echo $tweet['likes']; ?></button>
                        </form>
                    </div>

                    <!-- Sección de Comentarios -->
                    <div class="comments-section">
                        <h6>Comentarios</h6>
                        <?php if (isset($tweet['comments']) && is_array($tweet['comments']) && count($tweet['comments']) > 0): ?>
                            <?php foreach ($tweet['comments'] as $comment): ?>
                                <p><strong><?php echo $comment['username']; ?></strong>: <?php echo $comment['content']; ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No hay comentarios aún.</p>
                        <?php endif; ?>
                        <div class="tweet-actions">

                        <!-- Botón de Eliminar -->
                        <form action="eliminar_tweet.php" method="post">
                            <input type="hidden" name="tweet_id" value="<?php echo $tweet['_id']; ?>">
                            <button type="submit" class="delete-btn"><i class="fas fa-trash-alt"></i> Eliminar</button>
                        </form>
                    </div>

                        <!-- Formulario para agregar un comentario -->
                        <form action="tweets.php" method="post">
                            <input type="hidden" name="tweet_id" value="<?php echo $tweet['_id']; ?>">
                            <input type="text" name="comment_content" placeholder="Añadir un comentario" required>
                            <button type="submit">Comentar</button>
                        </form>
                        
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="right-sidebar">
        <div class="search-bar">
            <input type="text" placeholder="Buscar en Twitter">
        </div>
        <div class="premium-box">
            <h3>Suscripción Premium</h3>
            <p>Suscríbete para desbloquear nuevas funciones y, si eres elegible, recibir un pago de cuota de ingresos por anuncios.</p>
            <br>
            <button class="subscribe-btn">Suscribirse</button>
        </div>
        <div class="trending-box">
            <h3>Tendencias para ti</h3>
            <ul>
                <li>WhatsaApp</li>
                <p>130 mil post</p>
                <br>
                <li>Antártida</li>
                <p>1,028 post</p>
                <br>
                <li>Apagones</li>
                <p>10,4 mil post</p>
                <br>
                <li>Ucrania</li>
                <p>309,00 mil post</p>
                <br>
                <li>#Alerta</li>
                <p>30 mil post</p>
            </ul>
        </div>
    </div>
</body>
</html>
