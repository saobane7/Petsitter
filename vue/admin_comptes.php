<?php
$titre_page = 'Comptes utilisateurs';
$page_active = 'comptes';
require __DIR__ . '/partials/entete.php';
?>

<main class="conteneur page">
    <div class="page-titre">
        <p class="surtitre">Administration</p>
        <h1>Comptes utilisateurs</h1>
        <p class="texte-doux">Gérez les comptes et les rôles de la plateforme.</p>
    </div>

    <section class="carte tableau-carte">
        <div class="tableau-defilant">
            <table class="tableau">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>E-mail</th>
                        <th>Rôle</th>
                        <th>Annonces</th>
                        <th>Note moyenne</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comptes as $compte) : ?>
                        <?php $est_moi = (int) $compte['id_utilisateur'] === $idAdmin; ?>
                        <tr>
                            <td>
                                <div class="personne">
                                    <span class="avatar"><?= e(initiales($compte['prenom'], $compte['nom'])) ?></span>
                                    <strong><?= e(nom_complet($compte['prenom'], $compte['nom'])) ?></strong>
                                </div>
                            </td>
                            <td class="texte-doux"><?= e($compte['email']) ?></td>
                            <td>
                                <?php if ($est_moi) : ?>
                                    <span class="statut statut-badge statut-ouverte">Admin</span>
                                <?php else : ?>
                                    <form method="post" action="admin_comptes.php">
                                        <?php require __DIR__ . '/partials/csrf.php'; ?>
                                        <input type="hidden" name="action" value="role">
                                        <input type="hidden" name="id_utilisateur" value="<?= (int) $compte['id_utilisateur'] ?>">
                                        <select name="role" class="select-role <?= $compte['role'] === 'admin' ? 'select-admin' : '' ?>" data-envoi-auto aria-label="Rôle">
                                            <option value="utilisateur" <?= $compte['role'] === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                                            <option value="admin" <?= $compte['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                        <noscript><button type="submit" class="bouton bouton-contour bouton-petit">Ok</button></noscript>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td><?= (int) $compte['nb_annonces'] ?></td>
                            <td><strong><?= $compte['note_moyenne'] !== null ? e(note($compte['note_moyenne'])) . ' / 5' : '—' ?></strong></td>
                            <td>
                                <?php if (!$est_moi) : ?>
                                    <form method="post" action="admin_comptes.php" data-confirmer="Supprimer ce compte, ses annonces, candidatures et avis ?">
                                        <?php require __DIR__ . '/partials/csrf.php'; ?>
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id_utilisateur" value="<?= (int) $compte['id_utilisateur'] ?>">
                                        <button type="submit" class="bouton bouton-contour bouton-petit">Supprimer le compte</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
