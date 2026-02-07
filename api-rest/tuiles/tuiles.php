<?php

class Tuile {

	private $conn;
	private $table_name = "Tuiles";

	public $id;
	public $titre;
	public $description;
	public $date;
	public $priorite;
	public $realise;
	public $categorie;

	public function __construct($db) {
		$this->conn = $db;
	}
	

	public function read() {
		$query = "SELECT id, titre, description, date, priorite, realise, categorie
			FROM ". $this->table_name;
		
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		
		return $stmt;
	}

	public function create() {
		$query = "INSERT INTO" .$this->table_name. "
			SET id=:id, 
			description:=description,
			date:=date,
			priorite:=priorite,
			realise:=realise,
			categorie:=categorie";
		
		$stmt = $this->conn->prepare($query);

		//sanitize
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->titre = htmlspecialchars(strip_tags($this->titre));
		$this->description = htmlspecialchars(strip_tags($this->description));
		$this->date = htmlspecialchars(strip_tags($this->date));
		$this->priorite = htmlspecialchars(strip_tags($this->priorite));
		$this->categorie = htmlspecialchars(strip_tags($this->categorie));
		
		//bind value
		$stmt->bindParam(":id", $this->id);
		$stmt->bindParam(":titre", $this->titre);
		$stmt->bindParam(":description", $this->description);
		$stmt->bindParam(":date", $this->date);
		$stmt->bindParam(":priorite", $this->priorite);
		$stmt->bindParam(":realise", $this->realise);
		$stmt->bindParam(":categorie", $this->categorie);

		if ($stmt->execute()) {
			return true;
		}

		return false;
	}

	public function delete() {
		
		$query = "DELETE FROM " .$this->table_name. " WHERE id = ?";

		$stmt = $this->conn->prepare($query);

		//sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));
		$stmt->bindparam(1, $this->id);

		if ($stmt->execute()) {
			return true;
		}

		return false;
	}
}
