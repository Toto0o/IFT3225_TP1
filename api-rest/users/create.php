<?php

header("Acess-Control-Allow-Oriign: *");
header("Acess-Control-Allow-Headers: access");
header("Acess-Control-Allow-Methods: GET");
header("Acess-Control-Allow-Credentials: true");
header('Content-type : application/json; charset=UTF-8;');

include_once '../config/database.php';
include_once '../users/account.php';

$databse = new Database();
$db = $database->getConnection();

$account = new Account($db);

$data = json_decode(file_get_contents("php://input"));

if (	
	!empty($data->username) &&
	!empty($data->password) &&
	!empty($data->isAdmin)
) {

	if ($account->add_account($username, $password, $isAdmin)) {

		http_response_code(201);

		echo json_encode(array("message" => "Utilisateur crée"));
	} else {

		http_response_code(503);

		echo json_encode(array("message" => "Impossible de créer l'utilisateur"));
	}

} else {

	http_response_code(400);

	echo json_encode(array("message" => "Data incomplet, impossible de créer l'utilisateur"));
}


