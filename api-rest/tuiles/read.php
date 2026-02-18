<?php
// code pris dans les notes de cours : 7-rest.pdf p.24

header("Acess-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8;");

include_once '../config/databse.php';

$database = New Database();
$db = $database->getConnection();

$produc
