<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-type: application/json; charset=UTF-8;');

include_once '../config/database.php';
include_once '../tuiles/tuiles.php';

$database = new Database();
$db = $database->getConnection();

$tuile = new Tuile($db);

$data = json_decode(file_get_contents("php://input"));

if (
	// L'ID est créé automatiquement par la database
	!empty($data->titre) &&
	!empty($data->description) &&
	!empty($data->date) &&
	!empty($data->priorite) &&
	isset($data->realise)
) {
	$tuile->id = $data->id;
	$tuile->titre = $data->titre;
	$tuile->description = $data->description;
	$tuile->date = $data->date;
	$tuile->priorite = $data->priorite;
	$tuile->realise = $data->realise;
	$tuile->categorie_id = !empty($data->categorie_id) ? intval($data->categorie_id) : null;

	if ($tuile->create()) {

		http_response_code(201);

		echo json_encode(array("message" => "Tuile crée"));
	} else {

		http_response_code(503);

		echo json_encode(array("message" => "Impossible de créer la tuile"));
	}

} else {

	http_response_code(400);

	echo json_encode(array("message" => "Data incomplet, impossible de créerla tuile"));
}


