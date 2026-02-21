<?php
// code pris dans les notes de cours : 7-rest.pdf p.24

header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Oriign: *");
header("Acess-Control-Allow-Headers: access");
header("Acess-Control-Allow-Methods: GET");
header("Acess-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8;");

include_once '../config/database.php';
include_once '../users/account.php';

$database = New Database();
$db = $database->getConnection();

$account = new Account($db);

$data = json_decode(file_get_contents("php://input"));

$id = $account->getID_from_name($data['username']);

echo json_encode(array('id' => $id));

