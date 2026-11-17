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

        $this->verifierCompte();
    }

    // à chaque page, on relit le compte en base : s'il a été suspendu ou supprimé
    // pendant que l'utilisateur était connecté, la session est fermée tout de suite
    // (le rôle est aussi mis à jour si un admin l'a modifié)
    private function verifierCompte()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            return;
        }

        require_once __DIR__ . '/../model/utilisateur.php';
        $utilisateurModel = new Utilisateur();
        $compte = $utilisateurModel->trouverParId($_SESSION['id_utilisateur']);

        if ($compte === false || !$compte['actif']) {
            $_SESSION = ['erreur_connexion' => $compte === false ? "Ce compte n'existe plus." : 'Ce compte est suspendu.'];
            session_regenerate_id(true);
            $this->rediriger('connexion.php');
        }

        $_SESSION['role'] = $compte['role'];
        $_SESSION['nom'] = $compte['nom'];
        $_SESSION['prenom'] = $compte['prenom'];
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
            $this->interdit();
        }

        return $id;
    }

    protected function rediriger($url)
    {
        header('Location: ' . $url);
        exit;
    }

    // affiche la page d'erreur 404 : page ou ressource introuvable
    // $message (facultatif) précise ce qui manque, ex. "annonce introuvable."
    protected function introuvable($message = '')
    {
        http_response_code(404);
        $this->afficherErreur('404', $message);
    }

    // affiche la page d'erreur 403 : accès interdit
    protected function interdit($message = '')
    {
        http_response_code(403);
        $this->afficherErreur('403', $message);
    }

    // les pages d'erreur utilisent l'en-tête et le pied communs : elles ont besoin
    // des mêmes variables que les autres vues ($connecte, $flash, $csrf…)
    private function afficherErreur($code, $message)
    {
        extract($this->vue(['message_erreur' => $message]));
        require __DIR__ . '/../vue/erreurs/' . $code . '.php';
        exit;
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