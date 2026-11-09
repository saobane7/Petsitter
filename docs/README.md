# Documentation de conception

Ce dossier regroupe les livrables de conception de Petgarden. Ils sont versionnés avec le code pour rester synchronisés avec lui.

| Dossier | Contenu attendu | Formats conseillés |
| --- | --- | --- |
| [`maquette/`](maquette/) | Maquettes Figma (écrans, parcours) | lien Figma dans `maquette/README.md` + exports `.png` / `.pdf` |
| [`uml/`](uml/) | Diagrammes UML (cas d'utilisation, classes, séquence…) | source (`.drawio`, `.puml`, `.mmd`) + export `.png` / `.pdf` |
| [`mcd/`](mcd/) | Modèle conceptuel de données (et MLD si besoin) | source (`.mcd`, `.drawio`) + export `.png` / `.pdf` |

## Conventions

- Nommer les fichiers de façon explicite et datée si plusieurs versions coexistent : `mcd-v2.png`, `uml-classes-v1.pdf`.
- Toujours versionner la **source** éditable en plus de l'export image.
- Mentionner dans `CHANGELOG.md` toute évolution notable d'un livrable.
