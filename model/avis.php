<?php

require_once __DIR__ . '/../config/database.php';

class Avis
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function creer($note, $commentaire, $idAnnonce, $idAuteur, $idCible)
    {
        $requete = $this->pdo->prepare(
            'insert into avis (note, commentaire, id_annonce, id_auteur, id_cible)
             values (:note, :commentaire, :id_annonce, :id_auteur, :id_cible)'
        );
        $requete->execute([
            'note' => $note,
            'commentaire' => $commentaire,
            'id_annonce' => $idAnnonce,
            'id_auteur' => $idAuteur,
            'id_cible' => $idCible,
        ]);
    }

    // un seul avis par garde et par auteur
    public function existe($idAnnonce, $idAuteur)
    {
        $requete = $this->pdo->prepare(
            'select count(*) from avis where id_annonce = :id_annonce and id_auteur = :id_auteur'
        );
        $requete->execute(['id_annonce' => $idAnnonce, 'id_auteur' => $idAuteur]);

        return (int) $requete->fetchColumn() > 0;
    }

    // avis reçus par un utilisateur, du plus récent au plus ancien
    public function listerRecus($idCible, $limite = 20)
    {
        $requete = $this->pdo->prepare(
            'select v.*, u.prenom, u.nom
             from avis as v
             inner join utilisateur as u on u.id_utilisateur = v.id_auteur
             where v.id_cible = :id_cible
             order by v.date_avis desc
             limit ' . (int) $limite
        );
        $requete->execute(['id_cible' => $idCible]);

        return $requete->fetchAll();
    }

    // note moyenne et nombre d'avis reçus par un utilisateur
    public function reputation($idCible)
    {
        $requete = $this->pdo->prepare(
            'select round(avg(note), 1) as note_moyenne, count(*) as nb_avis
             from avis
             where id_cible = :id_cible'
        );
        $requete->execute(['id_cible' => $idCible]);

        return $requete->fetch();
    }

    // administration : derniers avis publiés avec auteur, cible et annonce
    public function listerRecents($limite = 50)
    {
        $requete = $this->pdo->query(
            'select v.*, a.titre,
                    au.prenom as prenom_auteur, au.nom as nom_auteur,
                    ci.prenom as prenom_cible, ci.nom as nom_cible
             from avis as v
             inner join annonces as a on a.id_annonce = v.id_annonce
             inner join utilisateur as au on au.id_utilisateur = v.id_auteur
             inner join utilisateur as ci on ci.id_utilisateur = v.id_cible
             order by v.date_avis desc, v.id_avis desc
             limit ' . (int) $limite
        );

        return $requete->fetchAll();
    }

    // administration : note moyenne de la plateforme et nombre d'avis publiés
    public function statistiques()
    {
        return $this->pdo->query(
            'select round(avg(note), 1) as note_moyenne, count(*) as nb_avis from avis'
        )->fetch();
    }

    public function supprimer($idAvis)
    {
        $requete = $this->pdo->prepare('delete from avis where id_avis = :id');
        $requete->execute(['id' => $idAvis]);
    }
}
