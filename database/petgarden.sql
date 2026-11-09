drop database if exists petgarden;
create database petgarden
character set utf8mb4
collate utf8mb4_unicode_ci;
use petgarden;

create table utilisateur (
    id_utilisateur int auto_increment,
    nom varchar(50) not null,
    prenom varchar(50) not null,
    email varchar(150) not null unique,
    mdp varchar(255) not null,
    role enum('utilisateur', 'admin') not null default 'utilisateur',
    constraint pk_utilisateur primary key (id_utilisateur)
);

create table annonce (
    id_annonce int auto_increment,
    titre varchar(150) not null,
    type_animal varchar(200) not null,
    ville varchar(100) not null,
    date_debut date not null,
    date_fin date not null,
    description text,
    statut enum('ouverte', 'pourvue', 'terminee') not null default 'ouverte',
    id_proprietaire int not null,
    constraint pk_annonce primary key (id_annonce),
    constraint fk_annonce_proprietaire foreign key (id_proprietaire) references utilisateur(id_utilisateur) on delete restrict,
    constraint chk_dates_annonce check (date_fin >= date_debut)
);

create table candidature (
    id_candidature int auto_increment,
    message text not null,
    statut enum('en attente', 'acceptee', 'refusee') not null default 'en attente',
    date_candidature datetime not null default current_timestamp,
    id_annonce int not null,
    id_gardien int not null,
    constraint pk_candidature primary key (id_candidature),
    constraint fk_candidature_annonce foreign key (id_annonce) references annonces(id_annonce) on delete restrict,
    constraint fk_candidature_gardien foreign key (id_gardien)  references utilisateur(id_utilisateur) on delete restrict,
    constraint uq_candidature unique (id_annonce, id_gardien)
);

create table avis (
    id_avis int auto_increment,
    id_annonce int not null,
    id_auteur int not null,
    id_cible int not null,
    note tinyint unsigned not null,
    commentaire varchar(150),
    date_avis datetime not null default current_timestamp,
    constraint pk_avis primary key (id_avis),
    constraint fk_avis_annonce foreign key (id_annonce) references annonces(id_annonce) on delete restrict,
    constraint fk_avis_auteur foreign key (id_auteur) references utilisateur(id_utilisateur) on delete restrict,
    constraint fk_avis_cible foreign key (id_cible) references utilisateur(id_utilisateur) on delete restrict,
    constraint chk_note_avis check (note between 1 and 5),
    constraint chk_auteur_cible check (id_auteur <> id_cible),
    constraint uq_avis_par_garde unique (id_annonce, id_auteur)
);




insert into utilisateur (id_utilisateur, nom, prenom, email, mdp, role) values
(1, 'amine', 'diallo', 'amine.diallo@gmail.com', '$2y$10$rXkAvLcWVhQKRCjdjJqHZO/NIbkaTc0SLXBukGsFAIV95R6bnlwo.', 'utilisateur'),
(2, 'sarah', 'martin', 'sarah.martin@yahoo.fr', '$2y$10$XQVskydiia7LkQvC9utDOuGIbWUOmRLl0yXYFe1Ir3wZBVqBlzT2.', 'utilisateur'),
(3, 'lucas', 'bernard', 'lucas.bernard@outlook.com', '$2y$10$OUYS4yLhBVK0CyOjJQ9agudq82e2PT7WxSjSXVoQ.cZzb.OsmpCjO', 'utilisateur'),
(4, 'emma', 'petit', 'emma.petit@gmail.com', '$2y$10$JqOxf0ITdtrSZ4TgXh29fuYPz4Uok7EwKmzly.IxGqyu/0yEN/zLi', 'utilisateur'),
(5, 'admin', 'petsitter', 'admin.petsitter@yahoo.fr', '$2y$10$KTFCNStz32k0n1WxmCOF1e1nKMzVLZ/m8Y8FRQ7kmbPEUpuUt6gE2', 'admin');

insert into annonces (id_annonce, titre, type_animal, ville, date_debut, date_fin, description, statut, id_proprietaire) values
(1, 'garde de mon chien', 'chien', 'saint-denis', '2026-11-01', '2026-11-05', 'promenade quotidienne necessaire.', 'ouverte', 1),
(2, 'garde de deux chats', 'chat', 'paris', '2026-10-20', '2026-10-25', 'nourrir les chats et nettoyer leur litiere.', 'pourvue', 2),
(3, 'garde de mon lapin', 'lapin', 'saint-ouen', '2026-09-01', '2026-09-05', 'donner du foin et de l eau.', 'terminee', 1),
(4, 'garde de mon chien pendant un week-end', 'chien', 'aubervilliers', '2026-09-10', '2026-09-12', 'garde et promenades.', 'terminee', 2);

insert into candidatures (id_candidature, message, statut, id_annonce, id_gardien) values
(1, 'j ai l habitude de garder des chiens.', 'en attente', 1, 2),
(2, 'je peux garder votre chien et le promener.', 'en attente', 1, 3),
(3, 'j aime beaucoup les chats.', 'acceptee', 2, 3),
(4, 'je suis disponible pour cette garde.', 'refusee', 2, 4),
(5, 'je peux garder votre lapin.', 'acceptee', 3, 3),
(6, 'je suis disponible ce week-end.', 'acceptee', 4, 4);

insert into avis (id_avis, note, commentaire, id_annonce, id_auteur, id_cible) values
(1, 5, 'tres bon gardien, serieux et attentionne.', 3, 1, 3),
(2, 4, 'proprietaire sympathique et organise.', 3, 3, 1),
(3, 5, 'tout s est tres bien passe.', 4, 2, 4);
