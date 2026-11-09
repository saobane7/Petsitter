# MCD PetGarden

Entités en bleu, associations en vert. Les identifiants sont soulignés.

```mermaid
flowchart LR
U["<b>UTILISATEUR</b><br/><u>id</u><br/>nom<br/>prenom<br/>email<br/>mdp<br/>role<br/>telephone<br/>ville<br/>code_postal<br/>a_propos<br/>photo<br/>est_client<br/>est_petsitter<br/>notifications_email<br/>presentation_petsitter<br/>disponible<br/>profil_verifie"]
E["<b>ESPECE</b><br/><u>id</u><br/>libelle"]
S["<b>SERVICE</b><br/><u>id</u><br/>libelle<br/>categorie"]
A["<b>ANIMAL</b><br/><u>id</u><br/>nom<br/>race"]
AN["<b>ANNONCE</b><br/><u>id</u><br/>titre<br/>type_animal<br/>ville<br/>date_debut<br/>date_fin<br/>horaire<br/>description<br/>statut"]
C["<b>CANDIDATURE</b><br/><u>id</u><br/>message<br/>statut<br/>date_candidature"]
AV["<b>AVIS</b><br/><u>id</u><br/>note<br/>commentaire<br/>date_avis"]
R["<b>RESERVATION</b><br/><u>id</u><br/>date_debut<br/>date_fin<br/>horaire<br/>montant<br/>statut<br/>date_demande"]
CR["<b>CRENEAU</b><br/><u>id</u><br/>date_creneau<br/>heure"]
F["<b>FACTURE</b><br/><u>id</u><br/>numero<br/>montant<br/>date_emission"]
P["<b>PAIEMENT</b><br/><u>id</u><br/>montant<br/>statut<br/>date_paiement"]
CO["<b>CONVERSATION</b><br/><u>id</u><br/>date_creation"]
M["<b>MESSAGE</b><br/><u>id</u><br/>type<br/>contenu<br/>date_envoi"]

a1(["POSSÉDER"])
a2(["ÊTRE DE"])
a3(["PUBLIER"])
a4(["CONCERNER"])
a5(["REQUÉRIR"])
a6(["DÉPOSER"])
a7(["RÉPONDRE À"])
a8(["ÉCRIRE"])
a9(["RECEVOIR"])
a10(["PORTER SUR"])
a11(["PROPOSER<br/>espèces"])
a12(["PROPOSER<br/>services<br/>tarif, unite"])
r1(["DEMANDER"])
r2(["ASSURER"])
r3(["CHOISIR"])
r4(["GARDER"])
r5(["ISSUE DE"])
r6(["PLANIFIER"])
r7(["FACTURER"])
r8(["RÉGLER"])
c1(["OUVRIR"])
c2(["PARTICIPER"])
c3(["PORTER SUR"])
m1(["APPARTENIR"])
m2(["ENVOYER"])
m3(["JOINDRE"])

U ---|"0,n"| a1
a1 ---|"1,1"| A
A ---|"1,1"| a2
a2 ---|"0,n"| E
U ---|"0,n"| a3
a3 ---|"1,1"| AN
AN ---|"0,1"| a4
a4 ---|"0,n"| A
AN ---|"0,1"| a5
a5 ---|"0,n"| S
U ---|"0,n"| a6
a6 ---|"1,1"| C
C ---|"1,1"| a7
a7 ---|"0,n"| AN
U ---|"0,n"| a8
a8 ---|"1,1"| AV
U ---|"0,n"| a9
a9 ---|"1,1"| AV
AV ---|"1,1"| a10
a10 ---|"0,n"| AN
U ---|"0,n"| a11
a11 ---|"0,n"| E
U ---|"0,n"| a12
a12 ---|"0,n"| S

U ---|"0,n"| r1
r1 ---|"1,1"| R
U ---|"0,n"| r2
r2 ---|"1,1"| R
R ---|"1,1"| r3
r3 ---|"0,n"| S
R ---|"1,1"| r4
r4 ---|"0,n"| A
AN ---|"0,n"| r5
r5 ---|"0,1"| R
CR ---|"1,1"| r6
r6 ---|"0,n"| R
F ---|"1,1"| r7
r7 ---|"0,1"| R
P ---|"1,1"| r8
r8 ---|"0,n"| R
U ---|"0,n"| c1
c1 ---|"1,1"| CO
U ---|"0,n"| c2
c2 ---|"1,1"| CO
CO ---|"0,1"| c3
c3 ---|"0,n"| AN
M ---|"1,1"| m1
m1 ---|"0,n"| CO
U ---|"0,n"| m2
m2 ---|"1,1"| M
M ---|"0,1"| m3
m3 ---|"0,n"| R

classDef ent fill:#E6F1FB,stroke:#185FA5,color:#0C447C,stroke-width:1px
classDef ass fill:#E1F5EE,stroke:#0F6E56,color:#085041,stroke-width:1px
class U,E,S,A,AN,C,AV,R,CR,F,P,CO,M ent
class a1,a2,a3,a4,a5,a6,a7,a8,a9,a10,a11,a12,r1,r2,r3,r4,r5,r6,r7,r8,c1,c2,c3,m1,m2,m3 ass
```

## Notes de lecture

- Les clés étrangères n'apparaissent pas comme attributs : elles sont remplacées par des associations avec leurs cardinalités.
- `UTILISATEUR` est relié plusieurs fois à `AVIS` (ÉCRIRE / RECEVOIR), à `RESERVATION` (DEMANDER / ASSURER) et à `CONVERSATION` (OUVRIR / PARTICIPER), selon le rôle joué.
- Les tables de jointure `petsitter_espece` et `petsitter_service` sont devenues les associations PROPOSER (N-N). Celle des services porte `tarif` et `unite`.
- `CRENEAU` est une entité faible : elle est identifiée par `RESERVATION` plus `date_creneau` et `heure`.
- `FACTURE` est en (1,1) côté facture et (0,1) côté réservation (contrainte `unique` sur `id_reservation`).
