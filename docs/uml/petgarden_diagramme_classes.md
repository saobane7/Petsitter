# Diagramme de classes UML – PetGarden

Diagramme généré à partir du MCD PetGarden. Il se visualise dans tout éditeur compatible Mermaid (GitHub, GitLab, VS Code, Obsidian, mermaid.live…).

```mermaid
classDiagram
direction LR

class Utilisateur {
  +int id
  +String nom
  +String prenom
  +String email
  +String mdp
  +String role
  +String telephone
  +String ville
  +String code_postal
  +String a_propos
  +String photo
  +boolean est_client
  +boolean est_petsitter
  +boolean notifications_email
  +String presentation_petsitter
  +boolean disponible
  +boolean profil_verifie
}

class Espece {
  +int id
  +String libelle
}

class Service {
  +int id
  +String libelle
  +String categorie
}

class PetsitterService {
  +float tarif
  +String unite
}

class Animal {
  +int id
  +String nom
  +String race
}

class Annonce {
  +int id
  +String titre
  +String type_animal
  +String ville
  +Date date_debut
  +Date date_fin
  +String horaire
  +String description
  +String statut
}

class Candidature {
  +int id
  +String message
  +String statut
  +DateTime date_candidature
}

class Avis {
  +int id
  +int note
  +String commentaire
  +DateTime date_avis
}

class Reservation {
  +int id
  +Date date_debut
  +Date date_fin
  +String horaire
  +float montant
  +String statut
  +DateTime date_demande
}

class Creneau {
  +int id
  +Date date_creneau
  +Time heure
}

class Facture {
  +int id
  +String numero
  +float montant
  +Date date_emission
}

class Paiement {
  +int id
  +float montant
  +String statut
  +DateTime date_paiement
}

class Conversation {
  +int id
  +DateTime date_creation
}

class Message {
  +int id
  +String type
  +String contenu
  +DateTime date_envoi
}

%% --- Animaux et espèces ---
Utilisateur "1" --> "0..*" Animal : possède
Animal "0..*" --> "1" Espece : est de

%% --- Annonces ---
Utilisateur "1" --> "0..*" Annonce : publie
Annonce "0..*" --> "0..1" Animal : concerne
Annonce "0..*" --> "0..1" Service : requiert

%% --- Candidatures ---
Utilisateur "1" --> "0..*" Candidature : dépose
Candidature "0..*" --> "1" Annonce : répond à

%% --- Avis ---
Utilisateur "1" --> "0..*" Avis : écrit (auteur)
Utilisateur "1" --> "0..*" Avis : reçoit (destinataire)
Avis "0..*" --> "1" Annonce : porte sur

%% --- Offre du petsitter (N-N) ---
Utilisateur "0..*" -- "0..*" Espece : propose
Utilisateur "1" -- "0..*" PetsitterService : propose
PetsitterService "0..*" -- "1" Service : porte sur

%% --- Réservations ---
Utilisateur "1" --> "0..*" Reservation : demande (client)
Utilisateur "1" --> "0..*" Reservation : assure (petsitter)
Reservation "0..*" --> "1" Service : choisit
Reservation "0..*" --> "1" Animal : garde
Annonce "0..1" --> "0..*" Reservation : donne lieu à
Reservation "1" *-- "0..*" Creneau : planifie
Reservation "0..1" -- "1" Facture : facturée par
Reservation "1" --> "0..*" Paiement : réglée par

%% --- Messagerie ---
Utilisateur "1" --> "0..*" Conversation : ouvre
Utilisateur "1" --> "0..*" Conversation : participe
Conversation "0..*" --> "0..1" Annonce : porte sur
Conversation "1" *-- "0..*" Message : contient
Utilisateur "1" --> "0..*" Message : envoie
Message "0..*" --> "0..1" Reservation : joint
```

## Notes de lecture

- Les clés étrangères du modèle relationnel ne figurent pas comme attributs : elles sont représentées par les associations et leurs multiplicités.
- `Utilisateur` est relié plusieurs fois à `Avis` (auteur / destinataire), `Reservation` (client / petsitter) et `Conversation` (ouvre / participe), selon le rôle joué.
- L'association N-N `propose` entre `Utilisateur` et `Espece` correspond à la table `petsitter_espece`.
- L'association N-N entre `Utilisateur` et `Service` (table `petsitter_service`) porte les attributs `tarif` et `unite` : elle est modélisée par la classe d'association `PetsitterService`.
- `Creneau` est une entité faible : elle est liée à `Reservation` par une composition (losange plein), donc supprimée avec la réservation.
- `Message` est lié à `Conversation` par une composition : un message n'existe que dans sa conversation.
- `Facture` est en `1` côté facture et `0..1` côté réservation (contrainte `unique` sur `id_reservation`).
- Les types des attributs (`String`, `int`, `Date`, `float`…) sont déduits des noms du MCD et sont à ajuster selon votre schéma réel.
