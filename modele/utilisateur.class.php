<?php

class Utilisateur{

private $id;
private $nom;
private $prenom;
private $email;
private $mdp;
private $role;

    public function  __construct($id, $nom, $prenom, $email, $mdp, $role){
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->role = $role;
    }


    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }

    
    public function getNom(){
        return $this->nom;
    }
    public function setNom($nom){
        $this->nom = $nom;
    }

    
    public function getPrenom(){
        return $this->nom;
    }
    public function setPrenom($prenom){
        $this->prenom = $prenom;
    }


    
    public function getEmail($email){
        return $this->email = $email;
    }
     public function setEmail($email){
        $this->email = $email;
    }

    
    public function getMdp($mdp){
        return $this->mdp = $mdp;
    }
    public function setMdp($mdp){
        $this->mdp = $mdp;
    }

    
    public function getRole($role){
        return $this->role = $role;
    }
    public function setRole($role){
        $this->role = $role;
    }
}

?>