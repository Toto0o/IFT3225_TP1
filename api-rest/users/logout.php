<?php

header("Acess-Control-Allow-Oriign: *");
header("Acess-Control-Allow-Headers: access");
header("Acess-Control-Allow-Methods: POST");
header("Acess-Control-Allow-Credentials: true");
header('Content-type : application/json; charset=UTF-8;');

include_once '../config/database.php';
include_once '../users/account.php';

$database = new Database();
$db = $database->getConnection();

$account = new Account($db);

if ($account->logout()) {
    http_response_code(200);
    echo json_encode(
        array('message' => 'Logout successful')
    );
} else {
    http_response_code(400);
    echo json_encode(
        array('message' => 'Logout failed')
    );
}