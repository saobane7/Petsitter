<?php

// petites fonctions d'affichage partagées par les vues (aucun accès à la base ici)

// échappe une valeur pour l'afficher dans le html
function e($valeur)
{
    return htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
}

// "garde de mon chien" -> "Garde de mon chien"
function majuscule($texte)
{
    $texte = (string) $texte;

    return mb_strtoupper(mb_substr($texte, 0, 1)) . mb_substr($texte, 1);
}

// "saint-denis" -> "Saint-Denis", "thomas bernard" -> "Thomas Bernard"
function titre($texte)
{
    return mb_convert_case((string) $texte, MB_CASE_TITLE, 'UTF-8');
}

// "Thomas Bernard"
function nom_complet($prenom, $nom)
{
    return titre($prenom . ' ' . $nom);
}

// "Thomas B."
function nom_court($prenom, $nom)
{
    return titre($prenom) . ' ' . mb_strtoupper(mb_substr((string) $nom, 0, 1)) . '.';
}

// "TB"
function initiales($prenom, $nom)
{
    return mb_strtoupper(mb_substr((string) $prenom, 0, 1) . mb_substr((string) $nom, 0, 1));
}

function nom_mois($numero)
{
    $mois = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet',
             'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    return $mois[(int) $numero - 1];
}

// "2026-11-04 10:12:00" -> "4 novembre"
function jour_mois($date)
{
    $horodatage = strtotime((string) $date);

    return date('j', $horodatage) . ' ' . nom_mois(date('n', $horodatage));
}

// "2026-11-04" -> "4 nov."
function jour_mois_court($date)
{
    $abreviations = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.',
                     'août', 'sept.', 'oct.', 'nov.', 'déc.'];
    $horodatage = strtotime((string) $date);

    return date('j', $horodatage) . ' ' . $abreviations[date('n', $horodatage) - 1];
}

// période d'une garde : "1 – 5 novembre", "28 octobre – 2 novembre", avec l'année en option
function periode($debut, $fin, $avecAnnee = false)
{
    $d = strtotime((string) $debut);
    $f = strtotime((string) $fin);
    $annee = $avecAnnee ? ' ' . date('Y', $f) : '';

    if (date('Y-m', $d) === date('Y-m', $f)) {
        if (date('j', $d) === date('j', $f)) {
            return date('j', $d) . ' ' . nom_mois(date('n', $d)) . $annee;
        }

        return date('j', $d) . ' – ' . date('j', $f) . ' ' . nom_mois(date('n', $f)) . $annee;
    }

    return jour_mois($debut) . ' – ' . jour_mois($fin) . $annee;
}

// version courte pour les étiquettes : "1 – 5 nov."
function periode_courte($debut, $fin)
{
    $d = strtotime((string) $debut);
    $f = strtotime((string) $fin);

    if (date('Y-m', $d) === date('Y-m', $f)) {
        return date('j', $d) . ' – ' . jour_mois_court($fin);
    }

    return jour_mois_court($debut) . ' – ' . jour_mois_court($fin);
}

// 4.8 -> "4,8"
function note($valeur)
{
    return number_format((float) $valeur, 1, ',', '');
}

// cinq étoiles, pleines jusqu'à la note arrondie
function etoiles($valeur, $classe = '')
{
    $pleines = (int) round((float) $valeur);
    $html = '<span class="etoiles ' . e($classe) . '" aria-label="' . e(note($valeur)) . ' sur 5">';

    for ($i = 1; $i <= 5; $i++) {
        $html .= '<span class="' . ($i <= $pleines ? 'pleine' : 'vide') . '">★</span>';
    }

    return $html . '</span>';
}

function libelle_statut($statut)
{
    $libelles = [
        'ouverte' => 'Ouverte',
        'pourvue' => 'Pourvue',
        'terminee' => 'Terminée',
        'en attente' => 'En attente',
        'acceptee' => 'Acceptée',
        'refusee' => 'Refusée',
    ];

    return $libelles[$statut] ?? majuscule($statut);
}

// classe css d'un statut : "en attente" -> "en-attente"
function classe_statut($statut)
{
    return str_replace(' ', '-', (string) $statut);
}

// "1 candidature", "4 candidatures"
function pluriel($nombre, $mot)
{
    return $nombre . ' ' . $mot . ((int) $nombre > 1 ? 's' : '');
}

// icône patte des cartes d'annonce
function patte($taille = 44)
{
    return '<svg class="patte" width="' . (int) $taille . '" height="' . (int) $taille . '" viewBox="0 0 44 44" aria-hidden="true">'
        . '<ellipse cx="22" cy="30" rx="11" ry="9"/>'
        . '<ellipse cx="9" cy="17" rx="4" ry="5.2" transform="rotate(-18 9 17)"/>'
        . '<ellipse cx="17.5" cy="9.5" rx="4" ry="5.4" transform="rotate(-6 17.5 9.5)"/>'
        . '<ellipse cx="26.5" cy="9.5" rx="4" ry="5.4" transform="rotate(6 26.5 9.5)"/>'
        . '<ellipse cx="35" cy="17" rx="4" ry="5.2" transform="rotate(18 35 17)"/>'
        . '</svg>';
}
