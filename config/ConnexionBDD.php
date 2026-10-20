<?php

$user = "root";
$mdp = "";
$url= "mysql:host=localhost;dbname= petgarden"

try{
    $pdo = new PDO($url,$user,$mdp);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_EXCEPTION);
}catch(PDOException $e){
    echo 'Erreur de connexion'e->getMessage();
}

?>