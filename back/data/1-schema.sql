CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

DROP TABLE IF EXISTS "users" CASCADE;

CREATE TABLE "public"."users" (
    "id" uuid NOT NULL,
    "nom" VARCHAR(100) NOT NULL,
    "prenom" VARCHAR(100) NOT NULL,
    "mail" VARCHAR(100) NOT NULL,
    "password" VARCHAR(100) NOT NULL,
    "role" INTEGER NOT NULL
);

ALTER TABLE users ADD PRIMARY KEY (id);

