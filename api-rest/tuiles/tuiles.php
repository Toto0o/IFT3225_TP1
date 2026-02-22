<?php
class Tuile {
    private $conn;
    private $table_name = "tuiles";

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
        $query = "SELECT t.id as id, t.titre, t.description, t.date, t.priorite, t.realise,
        	c.id as categorie_id, c.nom as categorie
            FROM " . $this->table_name . " t
            LEFT JOIN categories c ON t.categorie_id = c.id
            WHERE t.id = ?
            LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id           = $row['id'];
            $this->titre        = $row['titre'];
            $this->description  = $row['description'];
            $this->date         = $row['date'];
            $this->priorite     = $row['priorite'];
            $this->realise      = $row['realise'];
            $this->categorie_id = $row['categorie_id'];
        }
    }

    public function read() {
        $query = "SELECT t.id as id, t.titre, t.description, t.date, t.priorite, t.realise,
        	c.id as categorie_id, c.nom as categorie
            FROM " . $this->table_name . " t
            LEFT JOIN categories c ON t.categorie_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
            SET titre=:titre,
            description=:description,
            date=:date,
            priorite=:priorite,
            realise=:realise,
            categorie_id=:categorie_id";

        $stmt = $this->conn->prepare($query);

        $this->titre       = htmlspecialchars(strip_tags($this->titre ?? ''));
        $this->description = htmlspecialchars(strip_tags($this->description ?? ''));
        $this->date        = htmlspecialchars(strip_tags($this->date ?? ''));
        $this->priorite    = htmlspecialchars(strip_tags($this->priorite ?? ''));

        $stmt->bindParam(":titre",       $this->titre);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":date",        $this->date);
        $stmt->bindParam(":priorite",    $this->priorite);
        $stmt->bindParam(":realise",     $this->realise);
        if ($this->categorie_id === null) {
            $stmt->bindValue(":categorie_id", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(":categorie_id", intval($this->categorie_id), PDO::PARAM_INT);
        }

        if ($stmt->execute()) { return true; }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
            SET titre=:titre,
            description=:description,
            date=:date,
            priorite=:priorite,
            realise=:realise,
            categorie_id=:categorie_id
            WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->id          = intval($this->id);
        $this->titre       = htmlspecialchars(strip_tags($this->titre ?? ''));
        $this->description = htmlspecialchars(strip_tags($this->description ?? ''));
        $this->date        = htmlspecialchars(strip_tags($this->date ?? ''));
        $this->priorite    = htmlspecialchars(strip_tags($this->priorite ?? ''));

        $stmt->bindParam(":id",          $this->id);
        $stmt->bindParam(":titre",       $this->titre);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":date",        $this->date);
        $stmt->bindParam(":priorite",    $this->priorite);
        $stmt->bindParam(":realise",     $this->realise);
        if ($this->categorie_id === null) {
            $stmt->bindValue(":categorie_id", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(":categorie_id", intval($this->categorie_id), PDO::PARAM_INT);
        }

        if ($stmt->execute()) { return true; }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = intval($this->id);
        $stmt->bindParam(1, $this->id);
        if ($stmt->execute()) { return true; }
        return false;
    }
}