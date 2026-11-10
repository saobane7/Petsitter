<?php

class Database{
    private $host = 'localhost';
    private $dbname = 'petgarden';
    private $user = 'root';
    private $mdp = '';

    private static $pdo = null;

    public function getConnection(){
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4",
                    $this->user,
                    $this->mdp,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                http_response_code(500);
                exit('erreur de connexion à la base de données.');
            }
        }

        return self::$pdo;
    }
}
