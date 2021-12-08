<?php // classes.php

	class Requete {
		private $pdo; // Identifiant de connexion
		private $nom; // Nom de la requête
		private $req; // Requête à exécuter
		private $data; // Résultat de la requête


		function __construct($param_pdo, $param_req) {
			$this->pdo = $param_pdo;
			$this->req = $param_req;
		}

		//fonction pour execution de la requete 
		public function executer() {
			$res = $this->pdo->prepare($this->req);
			$res->execute();
			$this->data = $res->fetchAll(PDO::FETCH_ASSOC);
		}
		public function data() {
			return $this->data;
		}
	}
	class Tableau {
		private $data; //Données venant d'une requête

		function __construct($param_data, $param_nom) {
			$this->data = $param_data;		 
			$this->nom = $param_nom;
		}

		public function data() {
			return $this->data;
		}
	}
?>