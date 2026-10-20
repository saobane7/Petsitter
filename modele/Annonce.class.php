<?php

declare(strict_types=1);

class Annonce
{
    /** @return array<int, array<string, mixed>> */
    public function listerOuvertes(PDO $pdo): array
    {
        $requete = $pdo->query(
            "SELECT a.id, a.titre, a.type_animal, a.ville, a.date_debut,
                    a.date_fin, a.description, u.prenom, u.nom
             FROM annonces AS a
             INNER JOIN utilisateur AS u ON u.id = a.id_proprietaire
             WHERE a.statut = 'ouverte'
             ORDER BY a.date_debut ASC, a.id DESC"
        );

        return $requete->fetchAll();
    }
}
