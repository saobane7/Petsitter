drop database if exists petgarden;
create database petgarden
character set utf8mb4
collate utf8mb4_unicode_ci;
use petgarden;
set names utf8mb4;

create table utilisateur (
    id int auto_increment,
    nom varchar(50) not null,
    prenom varchar(50) not null,
    email varchar(150) not null unique,
    mdp varchar(255) not null,
    role enum('utilisateur', 'admin') not null default 'utilisateur',
    telephone varchar(20),
    ville varchar(100),
    code_postal varchar(10),
    a_propos text,
    photo varchar(255),
    est_client boolean not null default true,
    est_petsitter boolean not null default false,
    notifications_email boolean not null default true,
    presentation_petsitter varchar(255),
    disponible boolean not null default false,
    profil_verifie boolean not null default false,
    constraint pk_utilisateur primary key (id)
);

create table espece (
    id int auto_increment,
    libelle varchar(50) not null unique,
    constraint pk_espece primary key (id)
);

create table service (
    id int auto_increment,
    libelle varchar(100) not null unique,
    categorie enum('garde_hebergement', 'visite', 'promenade') not null,
    constraint pk_service primary key (id)
);

create table animal (
    id int auto_increment,
    nom varchar(50) not null,
    id_espece int not null,
    race varchar(100),
    id_proprietaire int not null,
    constraint pk_animal primary key (id),
    constraint fk_animal_espece foreign key (id_espece) references espece(id) on delete restrict,
    constraint fk_animal_proprietaire foreign key (id_proprietaire) references utilisateur(id) on delete cascade
);

create table annonces (
    id int auto_increment,
    titre varchar(150) not null,
    type_animal varchar(200) not null,
    ville varchar(100) not null,
    date_debut date,
    date_fin date,
    horaire varchar(100),
    description text,
    statut enum('ouverte', 'pourvue', 'terminee', 'brouillon') not null default 'ouverte',
    id_proprietaire int not null,
    id_animal int,
    id_service int,
    constraint pk_annonce primary key (id),
    constraint fk_annonce_proprietaire foreign key (id_proprietaire) references utilisateur(id) on delete cascade,
    constraint fk_annonce_animal foreign key (id_animal) references animal(id) on delete set null,
    foreign key (id_service) references service(id) on delete set null,
    constraint chk_dates_annonce check (date_fin >= date_debut),
    constraint chk_annonce_dates_obligatoires check (statut = 'brouillon' or (date_debut is not null and date_fin is not null))
);

create table candidatures (
    id int auto_increment,
    message text not null,
    statut enum('en attente', 'acceptee', 'refusee') not null default 'en attente',
    date_candidature datetime not null default current_timestamp,
    id_annonce int not null,
    id_gardien int not null,
    constraint pk_candidature primary key (id),
    constraint fk_candidature_annonce foreign key (id_annonce) references annonces(id) on delete cascade,
    constraint fk_candidature_gardien foreign key (id_gardien) references utilisateur(id) on delete cascade,
    constraint uq_candidature unique (id_annonce, id_gardien)
);

create table avis (
    id int auto_increment,
    id_annonce int not null,
    id_auteur int not null,
    id_cible int not null,
    note tinyint unsigned not null,
    commentaire varchar(150),
    date_avis datetime not null default current_timestamp,
    constraint pk_avis primary key (id),
    constraint fk_avis_annonce foreign key (id_annonce) references annonces(id) on delete restrict,
    constraint fk_avis_auteur foreign key (id_auteur) references utilisateur(id) on delete restrict,
    constraint fk_avis_cible foreign key (id_cible) references utilisateur(id) on delete restrict,
    constraint chk_note_avis check (note between 1 and 5),
    constraint chk_auteur_cible check (id_auteur <> id_cible),
    constraint uq_avis_par_garde unique (id_annonce, id_auteur)
);


create table reservation (
    id int auto_increment,
    id_proprietaire int not null,
    id_gardien int not null,
    id_service int not null,
    id_animal int not null,
    id_annonce int,
    date_debut date not null,
    date_fin date not null,
    horaire varchar(100),
    montant decimal(8, 2),
    statut enum('en attente', 'confirmee', 'refusee', 'terminee', 'annulee') not null default 'en attente',
    date_demande datetime not null default current_timestamp,
    constraint pk_reservation primary key (id),
    constraint fk_reservation_proprietaire foreign key (id_proprietaire) references utilisateur(id) on delete cascade,
    constraint fk_reservation_gardien foreign key (id_gardien) references utilisateur(id) on delete cascade,
    constraint fk_reservation_service foreign key (id_service) references service(id) on delete restrict,
    constraint fk_reservation_animal foreign key (id_animal) references animal(id) on delete cascade,
    constraint fk_reservation_annonce foreign key (id_annonce) references annonces(id) on delete set null,
    constraint chk_dates_reservation check (date_fin >= date_debut)
);

create table creneau (
    id int auto_increment,
    id_reservation int not null,
    date_creneau date not null,
    heure time,
    constraint pk_creneau primary key (id),
    constraint fk_creneau_reservation foreign key (id_reservation) references reservation(id) on delete cascade,
    constraint uq_creneau unique (id_reservation, date_creneau, heure)
);

create table conversation (
    id int auto_increment,
    id_proprietaire int not null,
    id_gardien int not null,
    id_annonce int,
    date_creation datetime not null default current_timestamp,
    constraint pk_conversation primary key (id),
    constraint fk_conversation_proprietaire foreign key (id_proprietaire) references utilisateur(id) on delete cascade,
    constraint fk_conversation_gardien foreign key (id_gardien) references utilisateur(id) on delete cascade,
    constraint fk_conversation_annonce foreign key (id_annonce) references annonces(id) on delete set null,
    constraint uq_conversation unique (id_proprietaire, id_gardien, id_annonce)
);

create table message (
    id int auto_increment,
    id_conversation int not null,
    id_expediteur int not null,
    type enum('texte', 'reservation') not null default 'texte',
    contenu text,
    id_reservation int,
    date_envoi datetime not null default current_timestamp,
    constraint pk_message primary key (id),
    constraint fk_message_conversation foreign key (id_conversation) references conversation(id) on delete cascade,
    constraint fk_message_expediteur foreign key (id_expediteur) references utilisateur(id) on delete cascade,
    constraint fk_message_reservation foreign key (id_reservation) references reservation(id) on delete cascade
);

create table petsitter_espece (
    id_gardien int not null,
    id_espece int not null,
    constraint pk_petsitter_espece primary key (id_gardien, id_espece),
    constraint fk_petsitter_espece_gardien foreign key (id_gardien) references utilisateur(id) on delete cascade,
    constraint fk_petsitter_espece_espece foreign key (id_espece) references espece(id) on delete cascade
);

create table petsitter_service (
    id_gardien int not null,
    id_service int not null,
    tarif decimal(6, 2) not null,
    unite enum('visite', 'jour') not null,
    constraint pk_petsitter_service primary key (id_gardien, id_service),
    constraint fk_petsitter_service_gardien foreign key (id_gardien) references utilisateur(id) on delete cascade,
    constraint fk_petsitter_service_service foreign key (id_service) references service(id) on delete cascade,
    constraint chk_tarif_positif check (tarif > 0)
);

create table facture (
    id int auto_increment,
    numero varchar(30) not null unique,
    id_reservation int not null unique,
    montant decimal(8, 2) not null,
    date_emission datetime not null default current_timestamp,
    constraint pk_facture primary key (id),
    constraint fk_facture_reservation foreign key (id_reservation) references reservation(id) on delete cascade
);

create table paiement (
    id int auto_increment,
    id_reservation int not null,
    montant decimal(8, 2) not null,
    statut enum('en attente', 'paye', 'rembourse') not null default 'en attente',
    date_paiement datetime,
    constraint pk_paiement primary key (id),
    constraint fk_paiement_reservation foreign key (id_reservation) references reservation(id) on delete cascade
);


insert into utilisateur (id, nom, prenom, email, mdp, role) values
(1, 'amine', 'diallo', 'amine.diallo@gmail.com', '$2y$10$rXkAvLcWVhQKRCjdjJqHZO/NIbkaTc0SLXBukGsFAIV95R6bnlwo.', 'utilisateur'),
(2, 'sarah', 'martin', 'sarah.martin@yahoo.fr', '$2y$10$XQVskydiia7LkQvC9utDOuGIbWUOmRLl0yXYFe1Ir3wZBVqBlzT2.', 'utilisateur'),
(3, 'lucas', 'bernard', 'lucas.bernard@outlook.com', '$2y$10$OUYS4yLhBVK0CyOjJQ9agudq82e2PT7WxSjSXVoQ.cZzb.OsmpCjO', 'utilisateur'),
(4, 'emma', 'petit', 'emma.petit@gmail.com', '$2y$10$JqOxf0ITdtrSZ4TgXh29fuYPz4Uok7EwKmzly.IxGqyu/0yEN/zLi', 'utilisateur'),
(5, 'admin', 'petsitter', 'admin.petsitter@yahoo.fr', '$2y$10$KTFCNStz32k0n1WxmCOF1e1nKMzVLZ/m8Y8FRQ7kmbPEUpuUt6gE2', 'admin');


-- mot de passe de test : prenom + 123)
insert into utilisateur (id, nom, prenom, email, mdp, role, telephone, ville, code_postal, a_propos, est_client, est_petsitter, notifications_email, presentation_petsitter,disponible, profil_verifie) values 
(6, 'Marchand', 'Léa', 'lea.marchand@exemple.fr', '$2y$10$cioQvGOzKi5/6Sf4slTOOuu0FjzSXEyezW3Z25OP8/40SnAXy8Hmy', 'utilisateur', '06 12 34 56 78', 'Évry-Courcouronnes', '91000',
'Maison avec jardin clos. Je garde chiens et chats depuis plusieurs années.', true, true, true, 'Maison avec jardin clos. Spécialiste des chiens de taille moyenne et des chats.', true, true),
--------------------
(7, 'Bernard', 'Thomas', 'thomas.bernard@exemple.fr', '$2y$10$0dDsuKQZnYJGwe/LeY6oIuFId5egRmYAmszwPv0yA0aotpFkpo5Le', 'utilisateur',
 null, 'Ris-Orangis', '91130', null, true, true, true, 'Disponible en soirée et le week-end pour promenades, visites et soins quotidiens.', true, true),
--------------------
(8, 'Khelifi', 'Sarah', 'sarah.khelifi@exemple.fr', '$2y$10$DqEaG9jN4qJpQ72gafoGp.Cj2WaR/lsuLElG3hl4Sfn.wWfwYWXIu', 'utilisateur',
 null, 'Corbeil-Essonnes', '91100', null, true, true, true,'Hébergement chez moi pour chats, lapins et petits rongeurs. Photos quotidiennes.', true, true);


insert into utilisateur (id, nom, prenom, email, mdp, role) values
(9, 'Roux', 'Camille', 'camille.roux@exemple.fr', '$2y$10$3fvaFq0/5NCm65jUW3DDV.i.2yV706A86rBXL2VIJ/43CR6wSL7hW', 'utilisateur'),
(10, 'Perrin', 'Julien', 'julien.perrin@exemple.fr', '$2y$10$zm8MYbc22QM/frPOInnJXOpB2uO/f5t7w3W8YtgxCCRkhcvhSahAe', 'utilisateur'),
(11, 'Dubois', 'Aline', 'aline.dubois@exemple.fr', '$2y$10$MbdjHVVHDBNGeDblyLcd6unW1TbYCB8D05MQhjzkfmzXCcfMR8ce.', 'utilisateur');

insert into espece (id, libelle) values
(1, 'chien'),
(2, 'chat'),
(3, 'lapin'),
(4, 'rongeur');

insert into service (id, libelle, categorie) values
(1, 'garde à domicile', 'garde_hebergement'),
(2, 'hébergement', 'garde_hebergement'),
(3, 'visite quotidienne', 'visite'),
(4, 'promenade', 'promenade');

insert into animal (id, nom, id_espece, race, id_proprietaire) values
(1, 'Gaston', 1, 'Cocker', 6),
(2, 'Choupette', 2, 'Européen', 6),
(3, 'Praline', 3, 'Bélier', 6),
(4, 'Milo', 2, null, 9),
(5, 'Rex', 1, null, 10),
(6, 'Noisette', 3, null, 11),
(7, 'Biscotte', 1, null, 4),
(8, 'Pixel', 2, null, 3),
(9, 'Ulysse', 1, null, 4);

insert into annonces (id, titre, type_animal, ville, date_debut, date_fin, description, statut, id_proprietaire) values
(1, 'garde de mon chien', 'chien', 'saint-denis', '2026-11-01', '2026-11-05', 'promenade quotidienne necessaire.', 'ouverte', 1),
(2, 'garde de deux chats', 'chat', 'paris', '2026-10-20', '2026-10-25', 'nourrir les chats et nettoyer leur litiere.', 'pourvue', 2),
(3, 'garde de mon lapin', 'lapin', 'saint-ouen', '2026-09-01', '2026-09-05', 'donner du foin et de l eau.', 'terminee', 1),
(4, 'garde de mon chien pendant un week-end', 'chien', 'aubervilliers', '2026-09-10', '2026-09-12', 'garde et promenades.', 'terminee', 2);

insert into annonces (id, titre, type_animal, ville, date_debut, date_fin, horaire, statut, id_proprietaire, id_animal, id_service) values
(5, 'Garde de Gaston pendant les fêtes', 'chien', 'Évry-Courcouronnes', '2026-12-20', '2026-12-27', null, 'ouverte', 6, 1, 1),
(6, 'Visites quotidiennes pour Choupette', 'chat', 'Évry-Courcouronnes', '2026-11-12', '2026-11-15', null, 'pourvue', 6, 2, 3),
(7, 'Promenades en semaine', 'chien', 'Évry-Courcouronnes', '2026-11-23', '2026-11-27', 'Chaque jour, 12h30', 'ouverte', 6, 1, 4),
(8, 'Garde du week-end de Gaston', 'chien', 'Évry-Courcouronnes', '2026-10-18', '2026-10-20', null, 'terminee', 6, 1, 1),
(9, 'Hébergement de Praline', 'lapin', 'Évry-Courcouronnes', null, null, null, 'brouillon', 6, 3, 2);

insert into candidatures (id, message, statut, id_annonce, id_gardien) values
(1, 'j ai l habitude de garder des chiens.', 'en attente', 1, 2),
(2, 'je peux garder votre chien et le promener.', 'en attente', 1, 3),
(3, 'j aime beaucoup les chats.', 'acceptee', 2, 3),
(4, 'je suis disponible pour cette garde.', 'refusee', 2, 4);

insert into candidatures (id, message, statut, id_annonce, id_gardien) values
(5, 'Je suis disponible pour garder Gaston pendant les fêtes.', 'en attente', 5, 7),
(6, 'J’ai de la place et un jardin pour un chien.', 'en attente', 5, 8),
(7, 'Je peux garder Gaston du 20 au 27 décembre.', 'en attente', 5, 3),
(8, 'Je suis disponible pendant les vacances.', 'en attente', 5, 4),
(9, 'Je peux passer chaque jour vers 8h30.', 'acceptee', 6, 7),
(10, 'Je suis disponible le midi en semaine.', 'en attente', 7, 7),
(11, 'Je peux promener Gaston à 12h30.', 'en attente', 7, 1),
(12, 'Je peux garder Gaston le week-end.', 'acceptee', 8, 8);

insert into avis (id, note, commentaire, id_annonce, id_auteur, id_cible) values
(1, 5, 'tres bon gardien, serieux et attentionne.', 3, 1, 3),
(2, 4, 'proprietaire sympathique et organise.', 3, 3, 1),
(3, 5, 'tout s est tres bien passe.', 4, 2, 4);

insert into reservation (id, id_proprietaire, id_gardien, id_service, id_animal, id_annonce, date_debut, date_fin, horaire, montant, statut) values
(1, 9, 6, 3, 4, null, '2026-11-03', '2026-11-05', '08:30', 54.00, 'confirmee'),
(2, 10, 6, 4, 5, null, '2026-11-02', '2026-11-06', null, 54.00, 'confirmee'),
(3, 4, 6, 1, 7, null, '2026-11-04', '2026-11-04', '14:00', 18.00, 'confirmee'),
(4, 11, 6, 2, 6, null, '2026-11-05', '2026-11-07', null, 72.00, 'confirmee'),
(5, 3, 6, 1, 8, null, '2026-11-12', '2026-11-15', null, null, 'en attente'),
(6, 4, 6, 4, 9, null, '2026-11-16', '2026-11-20', 'Chaque soir', null, 'en attente'),
(7, 6, 7, 3, 2, 6, '2026-11-12', '2026-11-15', '08:30', 60.00, 'confirmee'),
(8, 6, 8, 1, 1, 8, '2026-10-18', '2026-10-20', null, 72.00, 'terminee');

insert into creneau (id_reservation, date_creneau, heure) values
(2, '2026-11-02', '09:00'),
(1, '2026-11-03', '08:30'),
(2, '2026-11-03', '17:00'),
(1, '2026-11-04', '08:30'),
(3, '2026-11-04', '14:00'),
(4, '2026-11-05', null),
(1, '2026-11-05', '08:30'),
(4, '2026-11-06', null),
(2, '2026-11-06', '18:00'),
(4, '2026-11-07', null),
(7, '2026-11-12', '08:30'),
(7, '2026-11-13', '08:30'),
(7, '2026-11-14', '08:30'),
(7, '2026-11-15', '08:30');

insert into conversation (id, id_proprietaire, id_gardien, id_annonce) values
(1, 6, 7, 6),
(2, 6, 8, 8),
(3, 9, 6, null),
(4, 11, 6, null);

insert into message (id_conversation, id_expediteur, type, contenu, id_reservation, date_envoi) values
(1, 7, 'texte', 'Bonjour Léa, j’ai vu votre annonce pour Choupette. Je suis disponible du 12 au 15 novembre.', null, '2026-11-02 18:05:00'),
(1, 6, 'texte', 'Bonjour Thomas, parfait ! Elle a besoin d’une visite par jour : croquettes, eau fraîche et un peu de jeu.', null, '2026-11-02 18:12:00'),
(1, 7, 'texte', 'Aucun souci, je peux passer vers 8h30 avant mon travail.', null, '2026-11-02 18:20:00'),
(1, 6, 'reservation', null, 7, '2026-11-02 18:30:00'),
(1, 7, 'texte', 'Parfait, je passe demain à 8h30 !', null, '2026-11-03 10:42:00'),
(2, 6, 'texte', 'Merci pour les photos de Gaston, il a l’air en pleine forme !', null, '2026-11-02 20:15:00'),
(3, 9, 'texte', 'La clé est sous le pot de fleurs, à côté de la porte.', null, '2026-11-02 08:10:00'),
(4, 11, 'texte', 'Noisette mange du foin et des granulés, matin et soir.', null, '2026-10-30 19:00:00');

insert into petsitter_espece (id_gardien, id_espece) values
(6, 1), (6, 2),
(7, 1), (7, 2),
(8, 2), (8, 3), (8, 4);

insert into petsitter_service (id_gardien, id_service, tarif, unite) values
(6, 1, 18.00, 'visite'),
(6, 2, 24.00, 'jour'),
(6, 3, 18.00, 'visite'),
(6, 4, 18.00, 'visite'),
(7, 3, 15.00, 'visite'),
(7, 4, 15.00, 'visite'),
(8, 1, 24.00, 'jour'),
(8, 2, 24.00, 'jour');

insert into facture (numero, id_reservation, montant, date_emission) values
('F-2026-0001', 8, 72.00, '2026-10-20 18:00:00');

insert into paiement (id_reservation, montant, statut, date_paiement) values
(8, 72.00, 'paye', '2026-10-20 18:00:00'),
(7, 60.00, 'en attente', null);
