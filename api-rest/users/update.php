<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../users/account.php';

$database = new Database();
$db = $database->getConnection();
$account = new Account($db);

$data = json_decode(file_get_contents("php://input"));

if ($account->edit_account($data['id'], $data['data'])) {

	http_response_code(200);

	echo json_encode(array("message" => "Mise à jour réussie"));
}

else {
	http_response_code(503);

	echo json_encode(array('message' => "La mise à jour a échouée"));
}

