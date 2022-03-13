<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
}

include_once '../../models/BaseModel.php';
include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();

$author = new Author($db);

if($method == "GET"){
    $author->read();
} else if($method == "POST"){
    $author->create();
}else if($method == "PUT"){
    $author->update();
}else if($method == "DELETE"){
    $author->delete();
}

