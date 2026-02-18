<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT ID, Nom FROM Categories ORDER BY Nom ASC";
$stmt  = $db->prepare($query);
$stmt->execute();
$num = $stmt->rowCount();

if ($num > 0) {
    http_response_code(200);
    $categories = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categories[] = array(
            "id"  => $row['ID'],
            "nom" => $row['Nom']
        );
    }
    echo json_encode(array("records" => $categories));
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Aucune catégorie"));
}
