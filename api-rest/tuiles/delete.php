<?php

header("Access-Control-Allow-Oriign: *");
header("Access-Control-Allow-Methods: DELETE");
header('Content-type : application/json; charset=UTF-8;');

include_once '../config/database.php';
include_once '../tuiles/tuile.php';

$database = new Database();
$db = $database->getConnection();

$tuile = new Tuile($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $tuile->id = $data->id;

    if ($tuile->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Tuile effacée"));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Impossible d'effacer la tuile"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "ID manquant, impossible d'effacer la tuile"));
}
