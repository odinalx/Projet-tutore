CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

DROP TABLE IF EXISTS "lieux" CASCADE;
CREATE TABLE "public"."lieux" (
    "id" uuid PRIMARY KEY,
    "nom" VARCHAR(100) NOT NULL,
    "adresse" TEXT NOT NULL,
    "ville" VARCHAR(100) NOT NULL,
    "code_postal" VARCHAR(20) NOT NULL,    
    "created_at" TIMESTAMP DEFAULT NOW() NOT NULL,
    "updated_at" TIMESTAMP DEFAULT NOW() NOT NULL
);

INSERT INTO "activite" ("id", "nom", "description", "sections_id", "lieu_id", "date_debut", "date_fin", "created_at", "updated_at") VALUES
('1b2edf2d-0bdd-4ada-8b2a-f4fd28ed2f23',	'act01',	'test',	'500e3904-c41d-4d49-8d58-539f9e0d8b29',	'85327f5d-9c80-434f-a9c5-80b02c947b32',	'2025-02-24 15:55:18.520575',	'2025-02-24 15:55:18.520575',	'2025-02-24 15:55:18.520575',	'2025-02-24 15:55:18.520575');


DROP TABLE IF EXISTS "activite" CASCADE;
CREATE TABLE "public"."activite" (
    "id" uuid PRIMARY KEY,
    "nom" VARCHAR(100) NOT NULL,
    "description" TEXT,
    "sections_id" uuid NOT NULL,
    "lieu_id" uuid NOT NULL,
    "date_debut" TIMESTAMP NOT NULL,
    "date_fin" TIMESTAMP NOT NULL,
    "created_at" TIMESTAMP DEFAULT NOW() NOT NULL,
    "updated_at" TIMESTAMP DEFAULT NOW() NOT NULL,
    FOREIGN KEY ("sections_id") REFERENCES "sections"("id") ON DELETE CASCADE,
    FOREIGN KEY ("lieu_id") REFERENCES "lieux"("id") ON DELETE CASCADE
);

INSERT INTO "activite" ("id", "nom", "description", "sections_id", "lieu_id", "date_debut", "date_fin", "created_at", "updated_at") VALUES
('1b2edf2d-0bdd-4ada-8b2a-f4fd28ed2f23',	'act01',	'test',	'500e3904-c41d-4d49-8d58-539f9e0d8b29',	'85327f5d-9c80-434f-a9c5-80b02c947b32',	'2025-02-24 15:55:18.520575',	'2025-02-24 15:55:18.520575',	'2025-02-24 15:55:18.520575',	'2025-02-24 15:55:18.520575');



-- Création d'une vue permettant de récupérer les activités associées aux utilisateurs.

-- Il faut juste ajouter la clausule "where user_id="__" pour récupérer les activités d'un utilisateur en particulier.
-- Exemple: select * from user_activities where user_id='f4b3b9b1-5e0b-4aa7-9e6e-7f6d5c6f8f50';"


drop view if exists user_activities;
CREATE VIEW user_activities AS
SELECT u.id AS user_id,
    u.nom AS user_nom,
    u.prenom AS user_prenom,
    a.id AS activite_id,
    a.nom AS activite_nom,
    a.date_debut,
    a.date_fin,
    s.id AS section_id,
    s.nom AS section_nom
   FROM (((activite a
     JOIN sections s ON ((a.sections_id = s.id)))
     JOIN user_section us ON ((s.id = us.section_id)))
     JOIN users u ON ((us.user_id = u.id)));