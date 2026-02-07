<?php
// code pris dans les notes de cours : 7-rest.pdf p.21
	class Database{ 	
		private $host = "localhost"; // a modifier pour www-ens.iro.umontreal.ca 
		private $db_name = "gestion_tache"; 
		private $username = "root"; // a changer pour user sur ens
		private $password = "Doudou65!"; // a changer pour paswd du user 
		public $conn; 
		
		// get the database connection 
		public function getConnection(){ 
			$this->conn = null; 
			try{ 
				$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password); 
				$this->conn->exec("set names utf8"); 
			} catch(PDOException $exception){ 
				echo "Connection error: " . $exception->getMessage(); 
			} 
			return $this->conn; 
		} 
	} 
?>
