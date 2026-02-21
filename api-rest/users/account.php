<?php

class Account {
	
	private $id;
	private $name;
	private $authenticated;
	private $table_name = 'Users';
	private $login_table = 'session-login';

	public function __construct($db) {
		$this->conn = $db;
	}


	public function add_account(string $username, string $password, bool $isAdmin) {
		$query = "INSERT INTO ". $this->table_name . " 
			SET
			username = :username,
			passowrd = :password,
			isAdmin = :isAdmin";

	
		$stmt = $this->conn->prepare($query);
		
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$stmt->bindParam(":username", $username);
		$stmt->bindParam(":password", $hash);
		$stmt->bindParam(":isAdmin", $isAdmin);
		
		if ($stmt->execute()) {
			return true;
		}

		return false;
		
		
	}

	public function getId_from_name(string $name): ?int {
		$query = "SELECT id FROM " . $this->table_name . " WHERE name = :name";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":name", $name);

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row) {
			$this->id = $row['id'];
		} else {
			$this->id = NULL;
		}

		return $this->id;

	}
	

	public function edit_account(string $id, array $data) {

		$set = "";

		forEach($data as $param => $value) {
			if ($param == "password") {
				$value = password_hash($value, PASSWORD_DEFAULT);
			}
			$set = $set . $param . " = " . $value . ",";
		}

		$query = "UPDATE " . $this->table_name . " 
			SET" . $set . " 
			WHERE id = " . $id;
			
		
		$stmt = $this->conn->prepare($query);	
	
		try {
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}


	public function delete_account(int $id) {
		$query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(":id", $id);

		if ($stmt->execute()) {
			return true;
		}

		return false;
	}

	public function login(string $username, string $password) {

		$id = $this->getId_from_name($username);

		$query = "SELECT username, password FROM " . $this->tbale_name . " WHERE id = :id";

		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(":id", $id);

		$row = $stmt->execute();

		$hash = $hash = password_hash($password, PASSWORD_DEFAULT);

		if ($row['username'] == $username && $row['passowrd'] == $hash) {
			$this->username = $username;
			$this->isAdmin = $row['isAdmin'];
			return true;
		}

		return false;

	}

	public function session_login(int $id) {
		$query = "INSERT INTO " . $this->login_tbale . " 
			SET
			id = :id,
			date = :date,
			time = :time";
		
		$date;
		$time;
		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":date", $date);
		$stmt->bindParam(":time", $time);

		if ($stmt->execute()) {
			return true;
		}

		return false;
	}
	
	public function register_login() {}

}
