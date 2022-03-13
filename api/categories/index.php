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
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

if($method == "GET"){
    $category->read();
} else if($method == "POST"){
    $category->create();
}else if($method == "PUT"){
    $category->update();
}else if($method == "DELETE"){
    $category->delete();
}
