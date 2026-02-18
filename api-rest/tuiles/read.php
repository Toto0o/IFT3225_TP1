<?php
// code pris dans les notes de cours : 7-rest.pdf p.24

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Oriign: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8;");

include_once '../config/database.php';
include_once '../tuiles/tuile.php';

$database = New Database();
$db = $database->getConnection();

$tuile = new Tuile($db);

$stmt = $product->read();
$num = $stmt->rowCount();

if ($num>0) {

	http_response_code(200);

	$tuiles_arr = array();
	$tuiles_arr['records']=array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

		extract($row);

		$tuile_item = array (
			"id"           => $row['ID'],
            "titre"        => $row['Titre'],
            "description"  => $row['Description'],
            "date"         => $row['Date'],
            "priorite"     => $row['Priorite'],
            "realise"      => $row['Realise'],
            "categorie_id" => $row['categorie_id'],
            "categorie"    => $row['categorie']
		);
		array_push($tuiles_arr['records'], $tuile_item);
	}

	echo json_encode($tuiles_arr);
}
else {
	http_response_code(404);

	echo json_encode(array("message" => "Aucune tuile"));
}
