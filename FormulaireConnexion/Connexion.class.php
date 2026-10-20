<?php

declare(strict_types=1);

class Connexion
{
    /** Retourne l'utilisateur authentifié (sans son hash) ou null. */
    public function seConnecter(PDO $pdo, string $email, string $motDePasse): ?array
    {
        $requete = $pdo->prepare(
            'SELECT id, nom, prenom, email, mdp, role
             FROM utilisateur
             WHERE email = :email'
        );
        $requete->execute(['email' => $email]);
        $utilisateur = $requete->fetch();

        if (!$utilisateur || !password_verify($motDePasse, $utilisateur['mdp'])) {
            return null;
        }

        unset($utilisateur['mdp']);
        return $utilisateur;
    }
}
