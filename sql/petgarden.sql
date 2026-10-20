
create database if not exists petgarden
character set utf8mb4
collate utf8mb4_unicode_ci;

use petgarden;

-- 1. table utilisateur

create table utilisateur (
    id int auto_increment,
    nom varchar(50) not null,
    prenom varchar(50) not null,
    email varchar(150) not null unique,
    mdp varchar(255) not null,
    role enum('utilisateur', 'admin')
        not null default 'utilisateur',

    constraint pk_utilisateur primary key (id)
);

-- 2. table annonces

create table annonces (
    id int auto_increment,
    titre varchar(150) not null,
    type_animal varchar(200) not null,
    ville varchar(100) not null,
    date_debut date not null,
    date_fin date not null,
    description text,
    statut enum('ouverte', 'pourvue', 'terminee')
        not null default 'ouverte',
    id_proprietaire int not null,

    constraint pk_annonce primary key (id),

    constraint fk_annonce_proprietaire
        foreign key (id_proprietaire)
        references utilisateur(id)
        on delete restrict,

    constraint chk_dates_annonce
        check (date_fin >= date_debut)
);

-- 3. table candidatures

create table candidatures (
    id int auto_increment,
    message text not null,
    statut enum('en attente', 'acceptee', 'refusee')
        not null default 'en attente',
    date_candidature datetime not null default current_timestamp,
    id_annonce int not null,
    id_gardien int not null,

    constraint pk_candidature primary key (id),

    constraint fk_candidature_annonce
        foreign key (id_annonce)
        references annonces(id)
        on delete restrict,

    constraint fk_candidature_gardien
        foreign key (id_gardien)
        references utilisateur(id)
        on delete restrict,

    constraint uq_candidature
        unique (id_annonce, id_gardien)
);

-- 4. table avis

create table avis (
    id int auto_increment,
    id_annonce int not null,
    id_auteur int not null,
    id_cible int not null,
    note tinyint unsigned not null,
    commentaire varchar(150),
    date_avis datetime not null default current_timestamp,

    constraint pk_avis primary key (id),

    constraint fk_avis_annonce
        foreign key (id_annonce)
        references annonces(id)
        on delete restrict,

    constraint fk_avis_auteur
        foreign key (id_auteur)
        references utilisateur(id)
        on delete restrict,

    constraint fk_avis_cible
        foreign key (id_cible)
        references utilisateur(id)
        on delete restrict,

    constraint chk_note_avis
        check (note between 1 and 5),

    constraint chk_auteur_cible
        check (id_auteur <> id_cible),

    constraint uq_avis_par_garde
        unique (id_annonce, id_auteur)
);

-- 5. donnees de test

insert into utilisateur (id, nom, prenom, email, password, role) values
(1, 'amine', 'diallo', 'amine.diallo@gmail.com', 'amine123', 'utilisateur'),
(2, 'sarah', 'martin', 'sarah.martin@yahoo.fr', 'sarah123', 'utilisateur'),
(3, 'lucas', 'bernard', 'lucas.bernard@outlook.com', 'lucas123', 'utilisateur'),
(4, 'emma', 'petit', 'emma.petit@gmail.com', 'emma123', 'utilisateur'),
(5, 'admin', 'petsitter', 'admin.petsitter@yahoo.fr', 'admin123', 'admin');

insert into annonces
(id, titre, type_animal, ville, date_debut, date_fin, description, statut, id_proprietaire)
values
(1, 'garde de mon chien', 'chien', 'saint-denis',
 '2026-11-01', '2026-11-05',
 'promenade quotidienne necessaire.', 'ouverte', 1),

(2, 'garde de deux chats', 'chat', 'paris',
 '2026-10-20', '2026-10-25',
 'nourrir les chats et nettoyer leur litiere.', 'pourvue', 2),

(3, 'garde de mon lapin', 'lapin', 'saint-ouen',
 '2026-09-01', '2026-09-05',
 'donner du foin et de l eau.', 'terminee', 1),

(4, 'garde de mon chien pendant un week-end', 'chien', 'aubervilliers',
 '2026-09-10', '2026-09-12',
 'garde et promenades.', 'terminee', 2);

insert into candidatures
(id, message, statut, id_annonce, id_gardien)
values
(1, 'j ai l habitude de garder des chiens.', 'en attente', 1, 2),
(2, 'je peux garder votre chien et le promener.', 'en attente', 1, 3),
(3, 'j aime beaucoup les chats.', 'acceptee', 2, 3),
(4, 'je suis disponible pour cette garde.', 'refusee', 2, 4);

insert into avis
(id, note, commentaire, id_annonce, id_auteur, id_cible)
values
(1, 5, 'tres bon gardien, serieux et attentionne.', 3, 1, 3),
(2, 4, 'proprietaire sympathique et organise.', 3, 3, 1),
(3, 5, 'tout s est tres bien passe.', 4, 2, 4);
