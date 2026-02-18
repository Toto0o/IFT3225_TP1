<?php

header("Acess-Control-Allow-Oriign: *");
header("Acess-Control-Allow-Methods: DELETE");
header('Content-type : application/json; charset=UTF-8;');

include_once '../config/database.php';
include_once '../tuiles/tuile.php';

$database = new Database();
$db = $database->getConnection();

$tuile = new Tuile();

$data = json_decode(file_get_contents("php://input"));


$tuile->id = $data->id;

if ($tuile->delete()) {

	http_response_code(200); //ok

	echo json_encode(array("message" => "Tuile éffacée"));
} else {

	http_response_code(503); // service unavailable

	echo json_encode(array("message" => "Impossible d'effacer la tuile"));
}
