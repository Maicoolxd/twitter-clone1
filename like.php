<?php
require 'connect_data.php';

if (isset($_GET['id'])) {
    $tweetId = new MongoDB\BSON\ObjectId($_GET['id']);
    $tweet = $tweetsCollection->findOne(['_id' => $tweetId]);

    if ($tweet) {
        $tweetsCollection->updateOne(
            ['_id' => $tweetId],
            ['$inc' => ['likes' => 1]]
        );
    }
}

header("Location: index.php");
exit();
?>
