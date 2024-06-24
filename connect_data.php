<?php
require_once __DIR__ . '/vendor/autoload.php';

// Establecer conexión con MongoDB
$client = new MongoDB\Client('mongodb://localhost:27017');

// Seleccionar la base de datos y la colección
$database = $client->selectDatabase('mydb');
$usersCollection = $database->selectCollection('users');
$tweetsCollection = $database->selectCollection('tweets');
?>
