<?php

declare(strict_types=1);

class Inscription
{
    public function creer(PDO $pdo, string $nom, string $prenom, string $email, string $motDePasse): int
    {
        $requete = $pdo->prepare(
            'INSERT INTO utilisateur (nom, prenom, email, mdp)
             VALUES (:nom, :prenom, :email, :mdp)'
        );
        $requete->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mdp' => password_hash($motDePasse, PASSWORD_DEFAULT),
        ]);

        return (int) $pdo->lastInsertId();
    }
}
