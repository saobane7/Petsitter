# Guide MVC de Petgarden

Le MVC sépare le code en trois rôles. Chaque rôle a son dossier.

| | Dossier | Rôle | Il contient | Il ne doit **pas** contenir |
| --- | --- | --- | --- | --- |
| **M**odèle | `model/` | Parler à la base de données | Les requêtes SQL (une classe par table) | Du HTML, du `$_POST` |
| **V**ue | `vue/` | Afficher la page | Du HTML et des `echo` | Des requêtes SQL, de la logique |
| **C**ontrôleur | `controleur/` | Faire le lien | Lecture du formulaire, validation, appel du modèle | Du HTML, des requêtes SQL |

Le dossier `public/` contient une page d'entrée par écran : c'est ce fichier que le navigateur appelle (`mesAnnonces.php`, `Connexion.php`…). Il ne fait que relier le contrôleur et la vue.

## Le trajet d'une page

Exemple avec `mesAnnonces.php` :

1. `public/mesAnnonces.php` crée `AnnonceController` et appelle `afficherMesAnnonces()`.
2. Le contrôleur vérifie que l'utilisateur est connecté, puis demande ses annonces au modèle `Annonce`.
3. `Annonce::trouverParProprietaire()` exécute la requête SQL et retourne un tableau.
4. Le contrôleur retourne ce tableau à la page d'entrée.
5. `public/mesAnnonces.php` charge `vue/mesAnnonces.php`, qui affiche les annonces avec les variables disponibles (`$annonces`).

## Ajouter une page (exemple : `profil.php`)

1. **Modèle** (si besoin de données) : ajouter une méthode dans une classe de `model/`, ou créer `model/Profil.php`.
2. **Contrôleur** : ajouter une méthode dans une classe de `controleur/`, ou créer `controleur/ProfilController.php` :

   ```php
   require_once __DIR__ . '/../model/Utilisateur.php';

   class ProfilController
   {
       public function afficherProfil()
       {
           // vérifier la session, appeler le modèle, retourner les données
       }
   }
   ```

3. **Vue** : créer `vue/profil.php` avec le HTML. Afficher chaque donnée avec `htmlspecialchars(...)`.
4. **Page d'entrée** : créer `public/profil.php` :

   ```php
   <?php
   require_once __DIR__ . '/../controleur/ProfilController.php';

   $profilController = new ProfilController();
   $profil = $profilController->afficherProfil();

   require_once __DIR__ . '/../vue/profil.php';
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

Une page réservée aux utilisateurs connectés commence, dans son contrôleur, par :

```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: Connexion.php');
    exit;
}
```

## Règles à retenir

- Dans une vue, afficher toute donnée avec `htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8')`.
- Filtrer les annonces par propriétaire (`id_proprietaire`) pour qu'un utilisateur ne modifie que les siennes.
- Les fichiers CSS, images et JavaScript vont dans `public/assets/`.
