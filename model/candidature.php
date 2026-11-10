<?php

require_once __DIR__ . '/../config/database.php';

class Candidature
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function creer($message, $idAnnonce, $idGardien)
    {
        $requete = $this->pdo->prepare(
            'insert into candidatures (message, id_annonce, id_gardien)
             values (:message, :id_annonce, :id_gardien)'
        );
        $requete->execute([
            'message' => $message,
            'id_annonce' => $idAnnonce,
            'id_gardien' => $idGardien,
        ]);
    }

    public function trouverParAnnonceEtGardien($idAnnonce, $idGardien)
    {
        $requete = $this->pdo->prepare(
            'select * from candidatures
             where id_annonce = :id_annonce
             and id_gardien = :id_gardien'
        );
        $requete->execute(['id_annonce' => $idAnnonce, 'id_gardien' => $idGardien]);

        return $requete->fetch();
    }

    // candidatures reçues sur une annonce, avec la note moyenne de chaque gardien
    public function listerParAnnonce($idAnnonce)
    {
        $requete = $this->pdo->prepare(
            "select c.*, u.prenom, u.nom,
                    (select round(avg(v.note), 1) from avis as v where v.id_cible = u.id_utilisateur) as note_moyenne,
                    (select count(*) from avis as v where v.id_cible = u.id_utilisateur) as nb_avis
             from candidatures as c
             inner join utilisateur as u on u.id_utilisateur = c.id_gardien
             where c.id_annonce = :id_annonce
             order by field(c.statut, 'acceptee', 'en attente', 'refusee'), c.date_candidature asc"
        );
        $requete->execute(['id_annonce' => $idAnnonce]);

        return $requete->fetchAll();
    }

    // candidatures envoyées par un gardien, avec l'annonce et son propriétaire
    public function listerParGardien($idGardien, $statut = '')
    {
        $sql = 'select c.*, a.titre, a.type_animal, a.ville, a.date_debut, a.date_fin,
                       a.statut as statut_annonce, u.prenom, u.nom,
                       (select count(*) from avis as v
                        where v.id_annonce = a.id_annonce and v.id_auteur = c.id_gardien) as avis_laisse
                from candidatures as c
                inner join annonces as a on a.id_annonce = c.id_annonce
                inner join utilisateur as u on u.id_utilisateur = a.id_proprietaire
                where c.id_gardien = :id_gardien';
        $parametres = ['id_gardien' => $idGardien];

        if ($statut !== '') {
            $sql .= ' and c.statut = :statut';
            $parametres['statut'] = $statut;
        }

        $sql .= ' order by c.date_candidature desc';

        $requete = $this->pdo->prepare($sql);
        $requete->execute($parametres);

        return $requete->fetchAll();
    }

    // nombre de candidatures par statut : pour un gardien (colonne id_gardien) ou une annonce (id_annonce)
    public function compterParStatut($colonne, $id)
    {
        $colonne = $colonne === 'id_annonce' ? 'id_annonce' : 'id_gardien';

        $requete = $this->pdo->prepare(
            "select statut, count(*) as total
             from candidatures
             where $colonne = :id
             group by statut"
        );
        $requete->execute(['id' => $id]);

        $compteurs = ['en attente' => 0, 'acceptee' => 0, 'refusee' => 0];
        foreach ($requete->fetchAll() as $ligne) {
            $compteurs[$ligne['statut']] = (int) $ligne['total'];
        }

        return $compteurs;
    }

    // gardien retenu pour une annonce (ou false)
    public function gardienAccepte($idAnnonce)
    {
        $requete = $this->pdo->prepare(
            "select u.id_utilisateur, u.prenom, u.nom
             from candidatures as c
             inner join utilisateur as u on u.id_utilisateur = c.id_gardien
             where c.id_annonce = :id_annonce
             and c.statut = 'acceptee'"
        );
        $requete->execute(['id_annonce' => $idAnnonce]);

        return $requete->fetch();
    }

    // valider un gardien : sa candidature est acceptée, les autres refusées, l'annonce passe en "pourvue"
    public function valider($idCandidature, $idAnnonce)
    {
        $this->pdo->beginTransaction();

        try {
            $requete = $this->pdo->prepare(
                "update candidatures set statut = 'acceptee'
                 where id_candidature = :id and id_annonce = :id_annonce and statut = 'en attente'"
            );
            $requete->execute(['id' => $idCandidature, 'id_annonce' => $idAnnonce]);

            if ($requete->rowCount() !== 1) {
                $this->pdo->rollBack();
                return false;
            }

            $requete = $this->pdo->prepare(
                "update candidatures set statut = 'refusee'
                 where id_annonce = :id_annonce and id_candidature <> :id"
            );
            $requete->execute(['id' => $idCandidature, 'id_annonce' => $idAnnonce]);

            $requete = $this->pdo->prepare(
                "update annonces set statut = 'pourvue'
                 where id_annonce = :id_annonce and statut = 'ouverte'"
            );
            $requete->execute(['id_annonce' => $idAnnonce]);

            $this->pdo->commit();

            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
