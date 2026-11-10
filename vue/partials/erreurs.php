<?php if (!empty($erreurs)) : ?>
    <div class="alerte alerte-erreur">
        <?php foreach ($erreurs as $erreur) : ?>
            <p><?= e($erreur) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
