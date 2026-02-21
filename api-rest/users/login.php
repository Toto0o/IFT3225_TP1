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

$data = json_decode(file_get_contents("php://input"));

$account->username = $data->username;
$hash = password_hash($data->password, PASSWORD_DEFAULT);
$account->password = $hash;

if ($account->login($data['username'], $data['password'])) {
    http_response_code(200);
    echo json_encode(
        array('message' => 'Login successful')
    );
} else {
    http_response_code(401);
    echo json_encode(
        array('message' => 'Login failed')
    );
}
