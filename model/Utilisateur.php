
<?php

require_once __DIR__ . '/../config/database.php';

class Utilisateur
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function trouverParEmail($email){
        $requete = $this->pdo->prepare("select * from utilisateur where email = :email");
        $requete->execute(['email' => $email]);

        return $requete->fetch();
    }

    public function creer($nom, $prenom, $email, $mdp){
        $motDepasseHash = password_hash($mdp, PASSWORD_DEFAULT);
        
        $sql = "insert into utilisateur(nom, prenom, email, mdp) values(:nom, :prenom, :email, :mdp)";
        $requete = $this->pdo->prepare($sql);
        $requete->execute([
            'nom'=>$nom,
            'prenom'=>$prenom,
            'email'=>$email,
            'mdp'=>$motDepasseHash
        ]);
        return true;
    }

    public function seConnecter($email, $mdp){
        $utilisateur = $this->trouverParEmail($email);

        if(!$utilisateur){
            return false;
        }
        if(!password_verify($mdp, $utilisateur['mdp'])){
            return false;
        }

        return $utilisateur;
    }
    
    public function trouverParId($id)
    {
        $sql = "SELECT id, nom, prenom, email, role
            FROM utilisateur
            WHERE id = :id";
            $requete = $this->pdo->prepare($sql);
            
            $requete->execute([
            'id' => $id
        ]);

        return $requete->fetch();
    }


}
