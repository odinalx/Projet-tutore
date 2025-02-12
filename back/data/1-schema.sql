CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

DROP TABLE IF EXISTS "users" CASCADE;

CREATE TABLE "public"."users" (
    "id" uuid PRIMARY KEY,
    "nom" VARCHAR(100) NOT NULL,
    "prenom" VARCHAR(100) NOT NULL,
    "mail" VARCHAR(100) NOT NULL,
    "password" VARCHAR(100) NOT NULL,
    "role" INTEGER NOT NULL,
    "created_at" TIMESTAMP NOT NULL,
    "updated_at" TIMESTAMP NOT NULL
);


CREATE TABLE "public"."organismes" (
    "id" uuid PRIMARY KEY,
    "nom" VARCHAR(100) NOT NULL,
    "description" TEXT NOT NULL,
    "adresse" TEXT NOT NULL,
    "created_at" TIMESTAMP NOT NULL,
    "updated_at" TIMESTAMP NOT NULL
);

CREATE TABLE "public"."historique" (
    "id" uuid PRIMARY KEY,
    "table" VARCHAR(100) NOT NULL,
    "id_modif" uuid NOT NULL,
    "type_modif" VARCHAR(50),
    "ancienne_valeur" TEXT,
    "nouvelle_valeur" TEXT,
    "date_modif" TIMESTAMP NOT NULL,
    "user_id" uuid NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
