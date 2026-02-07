<?php
// code pris dans les notes de cours : 7-rest.pdf p.24

header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Oriign: *");
header("Acess-Control-Allow-Headers: access");
header("Acess-Control-Allow-Methods: GET");
header("Acess-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8;");

include_once '../config/database.php';
include_once '../tuiles/tuiles.php';

$database = New Database();
$db = $database->getConnection();

$product = new Tuile($db);

$stmt = $product->read();
$num = $stmt->rowCount();

if ($num>0) {

	http_response_code(200);

	$tuiles_arr = array();
	$tuiles_arr['records']=array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

		extract($row);

		$tuile_item = array (
			"id" => $id,
			"titre" => $titre,
			"description" => $description,
			"date" => $date,
			"priorite" => $priorite,
			"realise" => $realise,
			"categorie" => $categorie
		);
		array_push($tuiles_arr['records'], $tuile_item);
	}

	echo json_encode($tuiles_arr);
}
else {
	http_response_code(404);

	echo json_encode(array("message" => "Aucune tuile"));
}
