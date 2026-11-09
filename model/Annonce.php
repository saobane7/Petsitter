<?php

require_once __DIR__ . '/../config/database.php';

class Annonce
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    // Lister les annonces ouvertes (page publique)
    public function listerOuvertes()
    {
        $sql = "select a.id, a.titre, a.type_animal, a.ville, a.date_debut,
                       a.date_fin, a.description, u.prenom, u.nom
                from annonces as a
                inner join utilisateur as u on u.id = a.id_proprietaire
                where a.statut = 'ouverte'
                order by a.date_debut asc, a.id desc";

        $requete = $this->pdo->query($sql);

        return $requete->fetchAll();
    }

    // Créer une annonce
    public function creer($titre, $typeAnimal, $ville, $dateDebut, $dateFin, $description, $idProprietaire)
    {
        $sql = "INSERT INTO annonces
                (titre, type_animal, ville, date_debut, date_fin, description, id_proprietaire)
                VALUES
                (:titre, :type_animal, :ville, :date_debut, :date_fin, :description, :id_proprietaire)";

        $requete = $this->pdo->prepare($sql);

        $requete->execute([
            'titre' => $titre,
            'type_animal' => $typeAnimal,
            'ville' => $ville,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'description' => $description,
            'id_proprietaire' => $idProprietaire
        ]);

        return true;
    }

        public function trouverParProprietaire($idProprietaire)
    {
        $sql = "SELECT * FROM annonces
                WHERE id_proprietaire = :id_proprietaire";

        $requete = $this->pdo->prepare($sql);

        $requete->execute([
            'id_proprietaire' => $idProprietaire
        ]);

        return $requete->fetchAll();
    }

        public function trouverParIdEtProprietaire($idAnnonce, $idProprietaire)
    {
        $sql = "SELECT * FROM annonces
                WHERE id = :id
                AND id_proprietaire = :id_proprietaire";

        $requete = $this->pdo->prepare($sql);

        $requete->execute([
            'id' => $idAnnonce,
            'id_proprietaire' => $idProprietaire
        ]);

        return $requete->fetch();
    }

        public function modifier($idAnnonce, $titre, $typeAnimal, $ville, $dateDebut, $dateFin, $description, $idProprietaire)
    {
        $sql = "UPDATE annonces
        SET titre = :titre,
            type_animal = :type_animal,
            ville = :ville,
            date_debut = :date_debut,
            date_fin = :date_fin,
            description = :description
        WHERE id = :id
        AND id_proprietaire = :id_proprietaire";
        $requete = $this->pdo->prepare($sql);

        $requete->execute([
        'titre' => $titre,
        'type_animal' => $typeAnimal,
        'ville' => $ville,
        'date_debut' => $dateDebut,
        'date_fin' => $dateFin,
        'description' => $description,
        'id' => $idAnnonce,
        'id_proprietaire' => $idProprietaire

    ]);
    return true;

    }
        
}