<?php

    class Database{
        private $host = 'localhost';
        private $dbname = 'petgarden';
        private $user = 'root';
        private $mdp= '';

        public function getConnection(){
            try{
                $pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4", $this->user, $this->mdp);
                return $pdo;
            } catch(PDOException $e){
                echo "Erreur de connexion a la BDD" . $e->getMessage();
            }
        }
    }

?>