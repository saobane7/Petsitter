# Guide MVC de Petgarden

Le MVC sépare le code en trois rôles. Chaque rôle a son dossier.

| | Dossier | Rôle | Il contient | Il ne doit **pas** contenir |
| --- | --- | --- | --- | --- |
| **M**odèle | `model/` | Parler à la base de données | Les requêtes SQL (une classe par table) | Du HTML, du `$_POST` |
| **V**ue | `vue/` | Afficher la page | Du HTML et des `echo` | Des requêtes SQL, de la logique |
| **C**ontrôleur | `controleur/` | Faire le lien | Lecture du formulaire, validation, appel du modèle | Du HTML, des requêtes SQL |

Le dossier `public/` contient une page d'entrée par écran : c'est ce fichier que le navigateur appelle (`mes_annonces.php`, `connexion.php`…). Il ne fait que relier le contrôleur et la vue. Tous les noms de fichiers sont en minuscules, mots séparés par `_`.

## Le trajet d'une page

Exemple avec `mes_annonces.php` :

1. `public/mes_annonces.php` crée `AnnonceController` et appelle `mesAnnonces()`.
2. Le contrôleur vérifie que l'utilisateur est connecté (`exigerConnexion()`), puis demande ses annonces au modèle `Annonce`.
3. `Annonce::trouverParProprietaire()` exécute la requête SQL et retourne un tableau.
4. Le contrôleur retourne un tableau de données via `$this->vue([...])`, qui ajoute l'utilisateur connecté, le jeton CSRF et le message flash.
5. `public/mes_annonces.php` fait `extract($donnees)` puis charge `vue/mes_annonces.php`, qui affiche `$annonces`, `$compteurs`…

## Correspondance avec la maquette Figma

| Écran Figma | Page | Contrôleur::méthode | Vue |
| --- | --- | --- | --- |
| 00 Accueil | `index.php` | `AnnonceController::accueil` | `accueil.php` |
| 01 Connexion | `connexion.php` | `UtilisateurController::connexion` | `connexion.php` |
| 02 Inscription | `inscription.php` | `UtilisateurController::inscription` | `inscription.php` |
| 03 Mes annonces | `mes_annonces.php` | `AnnonceController::mesAnnonces` | `mes_annonces.php` |
| 04 Publier une annonce | `creer_annonce.php` (et `modifier_annonce.php`) | `AnnonceController::creer` / `modifier` | `creer_annonce.php` / `modifier_annonce.php` |
| 05 Candidatures reçues | `candidatures.php?id=` | `CandidatureController::recues` | `candidatures.php` |
| 06 Laisser un avis | `avis.php?id=` | `AvisController::laisser` | `avis.php` |
| 07 Annonces ouvertes | `annonces.php` | `AnnonceController::annonces` | `annonces.php` |
| 08 Détail et candidature | `annonce.php?id=` | `AnnonceController::detail` | `annonce.php` |
| 09 Mes candidatures | `mes_candidatures.php` | `CandidatureController::mesCandidatures` | `mes_candidatures.php` |
| 10 Profil public | `profil.php?id=` | `UtilisateurController::profil` | `profil.php` |
| 11 Modération | `admin_moderation.php` | `AdminController::moderation` | `admin_moderation.php` |
| 12 Comptes | `admin_comptes.php` | `AdminController::comptes` | `admin_comptes.php` |

## Ajouter une page (exemple : `profil.php`)

1. **Modèle** (si besoin de données) : ajouter une méthode dans une classe de `model/`, ou créer `model/profil.php`.
2. **Contrôleur** : ajouter une méthode dans une classe de `controleur/`, ou créer `controleur/profil_controller.php` :

   ```php
   require_once __DIR__ . '/controleur.php';
   require_once __DIR__ . '/../model/utilisateur.php';

   class ProfilController extends Controleur
   {
       public function afficherProfil()
       {
           $id = $this->exigerConnexion();
           // appeler le modèle, puis retourner les données
           return $this->vue(['profil' => (new Utilisateur())->trouverParId($id)]);
       }
   }
   ```

3. **Vue** : créer `vue/profil.php`. Définir `$titre_page` et `$page_active`, inclure `partials/entete.php` en haut et `partials/pied.php` en bas. Afficher chaque donnée avec `e(...)`.
4. **Page d'entrée** : créer `public/profil.php` :

   ```php
   <?php
   require_once __DIR__ . '/../controleur/profil_controller.php';

   $controleur = new ProfilController();
   $donnees = $controleur->afficherProfil();
   extract($donnees);

   require __DIR__ . '/../vue/profil.php';
   ```

Attention à la casse des noms de fichiers dans les `require` : sous Linux, `vue/profil.php` et `vue/Profil.php` sont deux fichiers différents.

## Utiliser la base de données

La connexion se règle dans `config/database.php` (classe `Database`). Chaque modèle ouvre sa connexion dans son constructeur :

```php
public function __construct()
{
    $database = new Database();
    $this->pdo = $database->getConnection();
}
```

Dans un modèle, toujours utiliser des requêtes préparées pour les valeurs venant de l'utilisateur :

```php
$requete = $this->pdo->prepare('select * from annonces where id = :id');
$requete->execute(['id' => $id]);
$annonce = $requete->fetch();
```

## Pages protégées

Tous les contrôleurs héritent de `Controleur` (`controleur/controleur.php`), qui démarre la session. Une page réservée commence par :

```php
$id = $this->exigerConnexion(); // redirige vers connexion.php si besoin
$this->exigerAdmin();           // pages d'administration
```

Chaque formulaire `post` contient `<?php require __DIR__ . '/partials/csrf.php'; ?>`, et le contrôleur teste `if ($this->estPost())`, qui vérifie le jeton.

## Règles à retenir

- Dans une vue, afficher toute donnée avec `e($valeur)` (raccourci de `htmlspecialchars` défini dans `vue/partials/fonctions.php`).
- Écrire le SQL en minuscules.
- Filtrer les annonces par propriétaire (`id_proprietaire`) pour qu'un utilisateur ne modifie que les siennes.
- Les fichiers CSS, images et JavaScript vont dans `public/assets/`.
