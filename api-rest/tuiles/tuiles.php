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
	public $categorie_id;

	public function __construct($db) {
		$this->conn = $db;
	}

	public function read_one() {
		$query = "SELECT t.ID, t.Titre, t.Description, t.Date, t.Priorite, t.Realise,
				c.ID as categorie_id, c.Nom as categorie
			FROM " . $this->table_name . " t
			LEFT JOIN Categories c ON t.Categorie_ID = c.ID
			WHERE t.ID = ?
			LIMIT 0,1";
		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		extract($row);

		$this->id           = $ID;
		$this->titre        = $Titre;
		$this->description  = $Description;
		$this->priorite     = $Priorite;
		$this->realise      = $Realise;
		$this->categorie_id = $categorie_id;
	}

	public function read() {
		$query = "SELECT t.ID, t.Titre, t.Description, t.Date, t.Priorite, t.Realise,
				c.ID as categorie_id, c.Nom as categorie
			FROM " . $this->table_name . " t
			LEFT JOIN Categories c ON t.Categorie_ID = c.ID";
		
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		
		return $stmt;
	}

	public function create() {
		$query = "INSERT INTO " . $this->table_name . "
			SET Titre=:titre,
			Description=:description,
			Date=:date,
			Priorite=:priorite,
			Realise=:realise,
			Categorie_ID=:categorie_id";
		
		$stmt = $this->conn->prepare($query);

		//sanitize
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->titre = htmlspecialchars(strip_tags($this->titre));
		$this->description = htmlspecialchars(strip_tags($this->description));
		$this->date = htmlspecialchars(strip_tags($this->date));
		$this->priorite = htmlspecialchars(strip_tags($this->priorite));
		$this->categorie_id = intval($this->categorie_id);
		
		//bind value
		$stmt->bindParam(":titre", $this->titre);
		$stmt->bindParam(":description", $this->description);
		$stmt->bindParam(":date", $this->date);
		$stmt->bindParam(":priorite", $this->priorite);
		$stmt->bindParam(":realise", $this->realise);
		$stmt->bindParam(":categorie_id", $this->categorie_id);

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
		$stmt->bindParam(1, $this->id);

		if ($stmt->execute()) {
			return true;
		}

		return false;
	}

	public function update() {
		$query = "UPDATE " . $this->table_name . "
			SET Titre=:titre,
			Description=:description,
			Date=:date,
			Priorite=:priorite,
			Realise=:realise,
			Categorie_ID=:categorie_id
			WHERE ID=:id";

		$stmt = $this->conn->prepare($query);

		// Sanitize
		$this->id           = intval($this->id);
		$this->titre        = htmlspecialchars(strip_tags($this->titre));
		$this->description  = htmlspecialchars(strip_tags($this->description));
		$this->date         = htmlspecialchars(strip_tags($this->date));
		$this->priorite     = htmlspecialchars(strip_tags($this->priorite));
		$this->categorie_id = intval($this->categorie_id);

		// Bind
		$stmt->bindParam(":id",           $this->id);
		$stmt->bindParam(":titre",        $this->titre);
		$stmt->bindParam(":description",  $this->description);
		$stmt->bindParam(":date",         $this->date);
		$stmt->bindParam(":priorite",     $this->priorite);
		$stmt->bindParam(":realise",      $this->realise);
		$stmt->bindParam(":categorie_id", $this->categorie_id);

		if ($stmt->execute()) {
			return true;
		}
		return false;
	}
