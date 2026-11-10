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

    // annonces ouvertes, avec filtres facultatifs (type d'animal, ville) et limite
    public function listerOuvertes($typeAnimal = '', $ville = '', $limite = null)
    {
        $sql = "select a.*, u.prenom, u.nom
                from annonces as a
                inner join utilisateur as u on u.id_utilisateur = a.id_proprietaire
                where a.statut = 'ouverte'";
        $parametres = [];

        if ($typeAnimal !== '') {
            $sql .= ' and a.type_animal = :type_animal';
            $parametres['type_animal'] = $typeAnimal;
        }

        if ($ville !== '') {
            $sql .= ' and a.ville like :ville';
            $parametres['ville'] = '%' . $ville . '%';
        }

        $sql .= ' order by a.date_debut asc, a.id_annonce desc';

        if ($limite !== null) {
            $sql .= ' limit ' . (int) $limite;
        }

        $requete = $this->pdo->prepare($sql);
        $requete->execute($parametres);

        return $requete->fetchAll();
    }

    // détail d'une annonce avec son propriétaire
    public function trouverParId($idAnnonce)
    {
        $requete = $this->pdo->prepare(
            'select a.*, u.prenom, u.nom
             from annonces as a
             inner join utilisateur as u on u.id_utilisateur = a.id_proprietaire
             where a.id_annonce = :id'
        );
        $requete->execute(['id' => $idAnnonce]);

        return $requete->fetch();
    }

    public function trouverParIdEtProprietaire($idAnnonce, $idProprietaire)
    {
        $requete = $this->pdo->prepare(
            'select * from annonces
             where id_annonce = :id
             and id_proprietaire = :id_proprietaire'
        );
        $requete->execute(['id' => $idAnnonce, 'id_proprietaire' => $idProprietaire]);

        return $requete->fetch();
    }

    // annonces d'un propriétaire, avec le nombre de candidatures et le gardien retenu
    public function trouverParProprietaire($idProprietaire, $statut = '')
    {
        $sql = "select a.*,
                       (select count(*) from candidatures as c where c.id_annonce = a.id_annonce) as nb_candidatures,
                       g.id_utilisateur as id_gardien, g.prenom as prenom_gardien, g.nom as nom_gardien,
                       (select count(*) from avis as v
                        where v.id_annonce = a.id_annonce and v.id_auteur = a.id_proprietaire) as avis_laisse
                from annonces as a
                left join candidatures as ca on ca.id_annonce = a.id_annonce and ca.statut = 'acceptee'
                left join utilisateur as g on g.id_utilisateur = ca.id_gardien
                where a.id_proprietaire = :id_proprietaire";
        $parametres = ['id_proprietaire' => $idProprietaire];

        if ($statut !== '') {
            $sql .= ' and a.statut = :statut';
            $parametres['statut'] = $statut;
        }

        $sql .= ' order by a.date_debut desc';

        $requete = $this->pdo->prepare($sql);
        $requete->execute($parametres);

        return $requete->fetchAll();
    }

    // nombre d'annonces d'un propriétaire par statut : ['ouverte' => 2, ...]
    public function compterParStatut($idProprietaire)
    {
        $requete = $this->pdo->prepare(
            'select statut, count(*) as total
             from annonces
             where id_proprietaire = :id_proprietaire
             group by statut'
        );
        $requete->execute(['id_proprietaire' => $idProprietaire]);

        $compteurs = ['ouverte' => 0, 'pourvue' => 0, 'terminee' => 0];
        foreach ($requete->fetchAll() as $ligne) {
            $compteurs[$ligne['statut']] = (int) $ligne['total'];
        }

        return $compteurs;
    }

    // gardien le plus souvent retenu sur les annonces d'un propriétaire
    public function gardienLePlusSollicite($idProprietaire)
    {
        $requete = $this->pdo->prepare(
            "select u.id_utilisateur, u.prenom, u.nom, count(*) as nb_gardes
             from candidatures as c
             inner join annonces as a on a.id_annonce = c.id_annonce
             inner join utilisateur as u on u.id_utilisateur = c.id_gardien
             where a.id_proprietaire = :id_proprietaire
             and c.statut = 'acceptee'
             group by u.id_utilisateur, u.prenom, u.nom
             order by nb_gardes desc
             limit 1"
        );
        $requete->execute(['id_proprietaire' => $idProprietaire]);

        return $requete->fetch();
    }

    public function creer($titre, $typeAnimal, $ville, $dateDebut, $dateFin, $description, $idProprietaire)
    {
        $requete = $this->pdo->prepare(
            'insert into annonces
             (titre, type_animal, ville, date_debut, date_fin, description, id_proprietaire)
             values
             (:titre, :type_animal, :ville, :date_debut, :date_fin, :description, :id_proprietaire)'
        );
        $requete->execute([
            'titre' => $titre,
            'type_animal' => $typeAnimal,
            'ville' => $ville,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'description' => $description,
            'id_proprietaire' => $idProprietaire,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function modifier($idAnnonce, $titre, $typeAnimal, $ville, $dateDebut, $dateFin, $description, $idProprietaire)
    {
        $requete = $this->pdo->prepare(
            'update annonces
             set titre = :titre,
                 type_animal = :type_animal,
                 ville = :ville,
                 date_debut = :date_debut,
                 date_fin = :date_fin,
                 description = :description
             where id_annonce = :id
             and id_proprietaire = :id_proprietaire'
        );
        $requete->execute([
            'titre' => $titre,
            'type_animal' => $typeAnimal,
            'ville' => $ville,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'description' => $description,
            'id' => $idAnnonce,
            'id_proprietaire' => $idProprietaire,
        ]);
    }

    // passe une annonce d'un statut à un autre (ex. pourvue -> terminee), seulement pour son propriétaire
    public function changerStatut($idAnnonce, $idProprietaire, $ancienStatut, $nouveauStatut)
    {
        $requete = $this->pdo->prepare(
            'update annonces
             set statut = :nouveau
             where id_annonce = :id
             and id_proprietaire = :id_proprietaire
             and statut = :ancien'
        );
        $requete->execute([
            'nouveau' => $nouveauStatut,
            'id' => $idAnnonce,
            'id_proprietaire' => $idProprietaire,
            'ancien' => $ancienStatut,
        ]);

        return $requete->rowCount() === 1;
    }

    // administration : nombre de gardes terminées
    public function compterTerminees()
    {
        return (int) $this->pdo->query("select count(*) from annonces where statut = 'terminee'")->fetchColumn();
    }
}
