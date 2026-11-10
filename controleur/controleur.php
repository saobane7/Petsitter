<?php

// classe de base des contrôleurs : session, accès, protection csrf, messages flash
class Controleur
{
    // types d'animaux proposés dans les formulaires et les filtres
    protected $typesAnimaux = ['chien', 'chat', 'lapin', 'oiseau', 'rongeur', 'poisson', 'reptile', 'autre'];

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // utilisateur connecté (id, prénom, nom, rôle) ou null
    protected function connecte()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            return null;
        }

        return [
            'id' => (int) $_SESSION['id_utilisateur'],
            'prenom' => $_SESSION['prenom'],
            'nom' => $_SESSION['nom'],
            'role' => $_SESSION['role'],
        ];
    }

    // page réservée aux utilisateurs connectés : retourne l'id de l'utilisateur
    protected function exigerConnexion()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            $this->rediriger('connexion.php');
        }

        return (int) $_SESSION['id_utilisateur'];
    }

    // page réservée aux administrateurs
    protected function exigerAdmin()
    {
        $id = $this->exigerConnexion();

        if ($_SESSION['role'] !== 'admin') {
            $this->rediriger('index.php');
        }

        return $id;
    }

    protected function rediriger($url)
    {
        header('Location: ' . $url);
        exit;
    }

    protected function introuvable($message = 'page introuvable.')
    {
        http_response_code(404);
        exit($message);
    }

    // jeton csrf de la session, à placer dans chaque formulaire post
    protected function jetonCsrf()
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf'];
    }

    protected function estPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        $jeton = $_POST['csrf'] ?? '';
        if (!is_string($jeton) || !hash_equals($this->jetonCsrf(), $jeton)) {
            http_response_code(400);
            exit('formulaire expiré, rechargez la page.');
        }

        return true;
    }

    // message affiché une seule fois, après une redirection
    protected function flash($message)
    {
        $_SESSION['flash'] = $message;
    }

    // données communes à toutes les vues + données propres à la page
    protected function vue(array $donnees = [])
    {
        $flash = $_SESSION['flash'] ?? '';
        unset($_SESSION['flash']);

        return array_merge([
            'connecte' => $this->connecte(),
            'csrf' => $this->jetonCsrf(),
            'flash' => $flash,
            'erreurs' => [],
        ], $donnees);
    }

    // lit un entier dans $_GET ou $_POST, ou retourne 0
    protected function entier($tableau, $cle)
    {
        $valeur = filter_var($tableau[$cle] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        return $valeur === false ? 0 : $valeur;
    }

    protected function texte($tableau, $cle)
    {
        $valeur = $tableau[$cle] ?? '';

        return is_string($valeur) ? trim($valeur) : '';
    }
}
