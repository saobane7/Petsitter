// petites améliorations d'interface : le site reste utilisable sans javascript

document.addEventListener('DOMContentLoaded', function () {
    var mois = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet',
                'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    // aperçu en direct de l'annonce (publier / modifier)
    var formulaire = document.getElementById('formulaire-annonce');
    if (formulaire) {
        var majuscule = function (texte) {
            return texte.charAt(0).toUpperCase() + texte.slice(1);
        };
        var periode = function (debut, fin) {
            if (!debut || !fin) {
                return 'Dates à renseigner';
            }
            var d = new Date(debut + 'T00:00');
            var f = new Date(fin + 'T00:00');
            if (d.getMonth() === f.getMonth() && d.getFullYear() === f.getFullYear()) {
                return d.getDate() + ' – ' + f.getDate() + ' ' + mois[f.getMonth()];
            }
            return d.getDate() + ' ' + mois[d.getMonth()] + ' – ' + f.getDate() + ' ' + mois[f.getMonth()];
        };
        var champ = function (nom) {
            return formulaire.querySelector('[name="' + nom + '"]');
        };
        var cible = function (nom) {
            return document.querySelector('[data-apercu="' + nom + '"]');
        };
        var mettreAJour = function () {
            var typeChoisi = champ('type_animal').value;
            cible('titre').textContent = champ('titre').value.trim() ? majuscule(champ('titre').value.trim()) : 'Titre de l’annonce';
            cible('type_animal').textContent = typeChoisi ? majuscule(typeChoisi) : 'Type d’animal';
            var ville = champ('ville').value.trim().toLowerCase().replace(/(^|[\s-])\S/g, function (lettre) {
                return lettre.toUpperCase();
            });
            cible('ville').textContent = ville || 'Ville';
            cible('dates').textContent = periode(champ('date_debut').value, champ('date_fin').value);
        };
        formulaire.addEventListener('input', mettreAJour);
        formulaire.addEventListener('change', mettreAJour);
    }

    // compteur de caractères ("0 / 150")
    document.querySelectorAll('[data-compteur]').forEach(function (zone) {
        var compteur = document.getElementById(zone.dataset.compteur);
        zone.addEventListener('input', function () {
            compteur.textContent = zone.value.length + ' / ' + zone.maxLength;
        });
    });

    // note choisie à côté des étoiles ("4 / 5")
    document.querySelectorAll('.etoiles-saisie input').forEach(function (bouton) {
        bouton.addEventListener('change', function () {
            var affichage = document.querySelector('[data-note-choisie]');
            if (affichage) {
                affichage.textContent = bouton.value + ' / 5';
            }
        });
    });

    // envoi automatique du formulaire au changement de rôle
    document.querySelectorAll('[data-envoi-auto]').forEach(function (liste) {
        liste.addEventListener('change', function () {
            liste.form.submit();
        });
    });

    // confirmation avant une suppression
    document.querySelectorAll('form[data-confirmer]').forEach(function (form) {
        form.addEventListener('submit', function (evenement) {
            if (!window.confirm(form.dataset.confirmer)) {
                evenement.preventDefault();
            }
        });
    });
});
