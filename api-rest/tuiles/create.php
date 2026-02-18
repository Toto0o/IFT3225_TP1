<?php

header("Acess-Control-Allow-Oriign: *");
header("Acess-Control-Allow-Headers: access");
header("Acess-Control-Allow-Methods: GET");
header("Acess-Control-Allow-Credentials: true");
header('Content-type : application/json; charset=UTF-8;');

include_once '../config/database.php';
include_once 'tuiles/tuile.php';

$databse = new Database();
$db = $database->getConnection();

$tuile = new Tuile($db);

$data = json_decode(file_get_contents("php://input"));

if (
	!empty($data->id) &&
	!empty($data->titre) &&
	!empty($data->description) &&
	!empty($data->date) &&
	!empty($data->priorite) &&
	!empty($data->realise) &&
	!empty($data->categorie)
) {
	$tuile->id = $data->id;
	$tuile->titre = $data->titre;
	$tuile->description = $data->description;
	$tuile->date = $data->date;
	$tuile->priorite = $data->priorite;
	$tuile->realise = $data->realise;
	$tuile->categorie = $data->categorie;

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


