-- ajoute la suspension des comptes sur une base déjà importée
alter table utilisateur
    add column actif boolean not null default true after role;