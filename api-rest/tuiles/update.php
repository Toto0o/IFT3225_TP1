<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once 'tuile.php';

$database = new Database();
$db = $database->getConnection();

$tuile = new Tuile($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->id) &&
    !empty($data->titre) &&
    !empty($data->description) &&
    !empty($data->date) &&
    !empty($data->priorite) &&
    isset($data->realise)
) {
    $tuile->id           = $data->id;
    $tuile->titre        = $data->titre;
    $tuile->description  = $data->description;
    $tuile->date         = $data->date;
    $tuile->priorite     = $data->priorite;
    $tuile->realise      = $data->realise;
    $tuile->categorie_id = $data->categorie_id ?? null;

    if ($tuile->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Tuile modifiée"));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Impossible de modifier la tuile"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Données incomplètes, impossible de modifier la tuile"));
}
