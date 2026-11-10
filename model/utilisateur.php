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

    public function trouverParEmail($email)
    {
        $requete = $this->pdo->prepare('select * from utilisateur where email = :email');
        $requete->execute(['email' => $email]);

        return $requete->fetch();
    }

    public function trouverParId($id)
    {
        $requete = $this->pdo->prepare(
            'select id_utilisateur, nom, prenom, email, role
             from utilisateur
             where id_utilisateur = :id'
        );
        $requete->execute(['id' => $id]);

        return $requete->fetch();
    }

    public function creer($nom, $prenom, $email, $mdp)
    {
        $requete = $this->pdo->prepare(
            'insert into utilisateur (nom, prenom, email, mdp)
             values (:nom, :prenom, :email, :mdp)'
        );
        $requete->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mdp' => password_hash($mdp, PASSWORD_DEFAULT),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    // retourne l'utilisateur si l'e-mail et le mot de passe sont bons, sinon false
    public function seConnecter($email, $mdp)
    {
        $utilisateur = $this->trouverParEmail($email);

        if (!$utilisateur || !password_verify($mdp, $utilisateur['mdp'])) {
            return false;
        }

        return $utilisateur;
    }

    // profil public : note moyenne, nombre d'avis reçus, gardes réalisées
    public function statistiques($id)
    {
        $requete = $this->pdo->prepare(
            "select
                (select round(avg(note), 1) from avis where id_cible = :id1) as note_moyenne,
                (select count(*) from avis where id_cible = :id2) as nb_avis,
                (select count(*)
                 from candidatures as c
                 inner join annonces as a on a.id_annonce = c.id_annonce
                 where c.id_gardien = :id3
                 and c.statut = 'acceptee'
                 and a.statut = 'terminee') as gardes_realisees"
        );
        $requete->execute(['id1' => $id, 'id2' => $id, 'id3' => $id]);

        return $requete->fetch();
    }

    // administration : tous les comptes avec leur nombre d'annonces et leur note moyenne
    public function listerTous()
    {
        $requete = $this->pdo->query(
            "select u.id_utilisateur, u.nom, u.prenom, u.email, u.role,
                    (select count(*) from annonces as a where a.id_proprietaire = u.id_utilisateur) as nb_annonces,
                    (select round(avg(v.note), 1) from avis as v where v.id_cible = u.id_utilisateur) as note_moyenne
             from utilisateur as u
             order by u.role desc, u.id_utilisateur asc"
        );

        return $requete->fetchAll();
    }

    public function compter()
    {
        return (int) $this->pdo->query('select count(*) from utilisateur')->fetchColumn();
    }

    public function changerRole($id, $role)
    {
        $requete = $this->pdo->prepare('update utilisateur set role = :role where id_utilisateur = :id');
        $requete->execute(['role' => $role, 'id' => $id]);
    }

    // les clés étrangères sont en "on delete restrict" : on supprime d'abord tout ce qui dépend du compte
    public function supprimer($id)
    {
        $this->pdo->beginTransaction();

        try {
            $requete = $this->pdo->prepare(
                "delete from avis
                 where id_auteur = :id1 or id_cible = :id2
                 or id_annonce in (select id_annonce from annonces where id_proprietaire = :id3)"
            );
            $requete->execute(['id1' => $id, 'id2' => $id, 'id3' => $id]);

            $requete = $this->pdo->prepare(
                "delete from candidatures
                 where id_gardien = :id1
                 or id_annonce in (select id_annonce from annonces where id_proprietaire = :id2)"
            );
            $requete->execute(['id1' => $id, 'id2' => $id]);

            $requete = $this->pdo->prepare('delete from annonces where id_proprietaire = :id');
            $requete->execute(['id' => $id]);

            $requete = $this->pdo->prepare('delete from utilisateur where id_utilisateur = :id');
            $requete->execute(['id' => $id]);

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
