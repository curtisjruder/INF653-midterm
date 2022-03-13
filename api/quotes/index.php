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
include_once '../../models/Quote.php';


$database = new Database();
$db = $database->connect();

echo "\nAbout to return quotes\n";
echo $method;

$quote = new Quote($db);

if($method == "GET"){
    echo "Read attempt";
    $quote->read();
} else if($method == "POST"){
    $quote->create();
}else if($method == "PUT"){
    $quote->update();
}else if($method == "DELETE"){
    $quote->delete();
}
