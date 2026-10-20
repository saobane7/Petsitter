<?php
Class Connexion{
    
    public function seConnecter($pdo, $email){
        $sql = "select * from user where email = :email";
        $requete = $pdo->prepare($sql);
        $requete->array([':email' => $email]);

        $user = $requete->fetch(PDO::FETCH_ASSOC);

    }

}



?>