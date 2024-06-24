<?php
require 'connect_data.php';

if (isset($_GET['id']) && isset($_POST['comment'])) {
    $tweetId = new MongoDB\BSON\ObjectId($_GET['id']);
    $comment = [
        'username' => $_SESSION['username'],
        'text' => $_POST['comment']
    ];

    $tweetsCollection->updateOne(
        ['_id' => $tweetId],
        ['$push' => ['comments' => $comment]]
    );
}

header("Location: index.php");
exit();
?>
