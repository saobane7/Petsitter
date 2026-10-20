<?php

declare(strict_types=1);

session_set_cookie_params([
    'httponly' => true,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
]);
session_start();

require_once __DIR__ . '/FormulaireConnexion/Connexion.class.php';
require_once __DIR__ . '/FormulaireConnexion/Inscription.class.php';
require_once __DIR__ . '/modele/Annonce.class.php';

function e(?string $valeur): string
{
    return htmlspecialchars($valeur ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function jetonCsrf(): string
{
    if (empty($_SESSION['jeton_csrf'])) {
        $_SESSION['jeton_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['jeton_csrf'];
}

function verifierJetonCsrf(): bool
{
    return isset($_POST['jeton_csrf'], $_SESSION['jeton_csrf'])
        && is_string($_POST['jeton_csrf'])
        && hash_equals($_SESSION['jeton_csrf'], $_POST['jeton_csrf']);
}

function champPoste(string $nom, bool $rogner = true): string
{
    $valeur = $_POST[$nom] ?? '';
    if (!is_string($valeur)) {
        return '';
    }

    return $rogner ? trim($valeur) : $valeur;
}

function rediriger(string $page = 'annonces'): void
{
    header('Location: ?page=' . rawurlencode($page));
    exit;
}

$pages = ['annonces', 'inscription', 'connexion', 'deconnexion'];
$page = $_GET['page'] ?? 'annonces';
if (!is_string($page) || !in_array($page, $pages, true)) {
    http_response_code(404);
    $page = 'annonces';
}

$pdo = null;
$erreurBase = null;
try {
    require __DIR__ . '/config/ConnexionBDD.php';
} catch (PDOException $exception) {
    // Ne pas exposer les identifiants ou les détails du serveur dans la page.
    $erreurBase = 'Connexion à la base de données impossible. Vérifiez la configuration locale et importez sql/petgarden.sql.';
}

$erreurs = [];
$valeurs = ['nom' => '', 'prenom' => '', 'email' => ''];
$utilisateurConnecte = $_SESSION['utilisateur'] ?? null;

if ($page === 'deconnexion') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifierJetonCsrf()) {
        $_SESSION = [];
        session_regenerate_id(true);
        session_destroy();
    } else {
        http_response_code(400);
    }
    rediriger();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($page, ['connexion', 'inscription'], true)) {
    if (!verifierJetonCsrf()) {
        $erreurs[] = 'Le formulaire a expiré. Réessayez.';
    } elseif (!$pdo instanceof PDO) {
        $erreurs[] = 'Le service est momentanément indisponible.';
    } elseif ($page === 'connexion') {
        $email = champPoste('email');
        $motDePasse = champPoste('mot_de_passe', false);
        $valeurs['email'] = $email;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150 || $motDePasse === '') {
            $erreurs[] = 'Adresse e-mail ou mot de passe invalide.';
        } else {
            $utilisateur = (new Connexion())->seConnecter($pdo, $email, $motDePasse);
            if ($utilisateur === null) {
                $erreurs[] = 'Adresse e-mail ou mot de passe invalide.';
            } else {
                session_regenerate_id(true);
                $_SESSION['utilisateur'] = $utilisateur;
                rediriger();
            }
        }
    } else {
        $nom = champPoste('nom');
        $prenom = champPoste('prenom');
        $email = champPoste('email');
        $motDePasse = champPoste('mot_de_passe', false);
        $valeurs = compact('nom', 'prenom', 'email');

        if ($nom === '' || mb_strlen($nom) > 50) {
            $erreurs[] = 'Le nom est obligatoire et doit faire au plus 50 caractères.';
        }
        if ($prenom === '' || mb_strlen($prenom) > 50) {
            $erreurs[] = 'Le prénom est obligatoire et doit faire au plus 50 caractères.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            $erreurs[] = 'Saisissez une adresse e-mail valide (150 caractères maximum).';
        }
        if (strlen($motDePasse) < 8) {
            $erreurs[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if ($erreurs === []) {
            try {
                $id = (new Inscription())->creer($pdo, $nom, $prenom, $email, $motDePasse);
                session_regenerate_id(true);
                $_SESSION['utilisateur'] = [
                    'id' => $id,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'role' => 'utilisateur',
                ];
                rediriger();
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000') {
                    $erreurs[] = 'Cette adresse e-mail est déjà utilisée.';
                } else {
                    $erreurs[] = 'Inscription impossible pour le moment.';
                }
            }
        }
    }
}

$annonces = [];
if ($page === 'annonces' && $pdo instanceof PDO) {
    try {
        $annonces = (new Annonce())->listerOuvertes($pdo);
    } catch (PDOException $exception) {
        $erreurBase = 'Impossible de charger les annonces. Vérifiez que le schéma SQL est importé.';
    }
}

$jeton = jetonCsrf();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Petgarden — garde d’animaux</title>
    <style>
        :root { color-scheme: light; font-family: system-ui, sans-serif; color: #20312b; background: #f5f8f5; }
        body { margin: 0; }
        header, main { max-width: 960px; margin: 0 auto; padding: 1rem 1.25rem; }
        header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
        a { color: #176b46; }
        nav { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .carte { background: white; border: 1px solid #dce7df; border-radius: .75rem; padding: 1rem; margin: 1rem 0; }
        .erreur { color: #8b1f1f; background: #fff0f0; padding: .75rem 1rem; border-radius: .5rem; }
        label { display: block; margin: .8rem 0 .25rem; }
        input { box-sizing: border-box; width: 100%; max-width: 28rem; padding: .6rem; }
        button { margin-top: 1rem; padding: .65rem 1rem; cursor: pointer; }
        form.inline { display: inline; }
        .inline button { margin: 0; }
        .meta { color: #50655a; }
    </style>
</head>
<body>
<header>
    <a href="?page=annonces"><strong>Petgarden</strong></a>
    <nav aria-label="Navigation principale">
        <a href="?page=annonces">Annonces</a>
        <?php if ($utilisateurConnecte): ?>
            <span>Bonjour <?= e($utilisateurConnecte['prenom'] ?? '') ?></span>
            <form class="inline" method="post" action="?page=deconnexion">
                <input type="hidden" name="jeton_csrf" value="<?= e($jeton) ?>">
                <button type="submit">Déconnexion</button>
            </form>
        <?php else: ?>
            <a href="?page=connexion">Connexion</a>
            <a href="?page=inscription">Inscription</a>
        <?php endif; ?>
    </nav>
</header>
<main>
    <h1><?= $page === 'connexion' ? 'Connexion' : ($page === 'inscription' ? 'Créer un compte' : 'Annonces de garde') ?></h1>

    <?php if ($erreurBase !== null): ?>
        <p class="erreur" role="alert"><?= e($erreurBase) ?></p>
    <?php endif; ?>
    <?php foreach ($erreurs as $erreur): ?>
        <p class="erreur" role="alert"><?= e($erreur) ?></p>
    <?php endforeach; ?>

    <?php if ($page === 'connexion'): ?>
        <form class="carte" method="post" action="?page=connexion">
            <input type="hidden" name="jeton_csrf" value="<?= e($jeton) ?>">
            <label for="email">Adresse e-mail</label>
            <input id="email" name="email" type="email" maxlength="150" autocomplete="email" required value="<?= e($valeurs['email']) ?>">
            <label for="mot_de_passe">Mot de passe</label>
            <input id="mot_de_passe" name="mot_de_passe" type="password" autocomplete="current-password" required>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore de compte ? <a href="?page=inscription">Inscrivez-vous</a>.</p>
    <?php elseif ($page === 'inscription'): ?>
        <form class="carte" method="post" action="?page=inscription">
            <input type="hidden" name="jeton_csrf" value="<?= e($jeton) ?>">
            <label for="prenom">Prénom</label>
            <input id="prenom" name="prenom" maxlength="50" autocomplete="given-name" required value="<?= e($valeurs['prenom']) ?>">
            <label for="nom">Nom</label>
            <input id="nom" name="nom" maxlength="50" autocomplete="family-name" required value="<?= e($valeurs['nom']) ?>">
            <label for="email">Adresse e-mail</label>
            <input id="email" name="email" type="email" maxlength="150" autocomplete="email" required value="<?= e($valeurs['email']) ?>">
            <label for="mot_de_passe">Mot de passe (8 caractères minimum)</label>
            <input id="mot_de_passe" name="mot_de_passe" type="password" minlength="8" autocomplete="new-password" required>
            <button type="submit">Créer mon compte</button>
        </form>
    <?php else: ?>
        <?php if ($annonces === [] && $erreurBase === null): ?>
            <p>Aucune annonce ouverte pour le moment.</p>
        <?php endif; ?>
        <?php foreach ($annonces as $annonce): ?>
            <article class="carte">
                <h2><?= e($annonce['titre']) ?></h2>
                <p class="meta"><?= e($annonce['type_animal']) ?> · <?= e($annonce['ville']) ?></p>
                <p>Du <?= e($annonce['date_debut']) ?> au <?= e($annonce['date_fin']) ?></p>
                <?php if (!empty($annonce['description'])): ?>
                    <p><?= nl2br(e($annonce['description'])) ?></p>
                <?php endif; ?>
                <p class="meta">Publié par <?= e($annonce['prenom'] . ' ' . $annonce['nom']) ?></p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</main>
</body>
</html>
