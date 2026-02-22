<?php

header("Acess-Control-Allow-Oriign: *");
header("Acess-Control-Allow-Headers: access");
header("Acess-Control-Allow-Methods: GET");
header("Acess-Control-Allow-Credentials: true");
header('Content-type: application/json; charset=UTF-8;');

include_once '../config/database.php';
include_once '../tuiles/tuiles.php';

$database = new Database();
$db = $database->getConnection();

$tuile = new Tuile($db);

$tuile->id isset($_GET['id'] ? $_GET['id'] : die();

$tuile->readOne();

if ($tuile->titre != null) {

	$tuile_arr = array(
		"id" => $tuile->id,
		"titre" => $tuile->titre,
		"description" => $tuile->description,
		"date" => $tuile->date,
		"priorite" => $tuile->priorite,
		"realise" => $tuile->realise,
		"categorie" => $tuile->categorie
	);

	http_response_code(200);

	echo (json_encode($tuile_arr));
}
else {
	http_response_code(404);

	echo json_encode(array("message" => "La tuile (id : " . $tuile->id . " n'existe pas"));
}
