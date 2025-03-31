-- Adminer 4.8.1 PostgreSQL 17.2 (Debian 17.2-1.pgdg120+1) dump

DROP TABLE IF EXISTS "champ";
CREATE TABLE "public"."champ" (
    "id" uuid NOT NULL,
    "nom" character varying(100) NOT NULL,
    "description" text NOT NULL,
    "created_at" timestamp NOT NULL,
    "updated_at" timestamp NOT NULL,
    CONSTRAINT "champ_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "champ" ("id", "nom", "description", "created_at", "updated_at") VALUES
('0c049e3f-6b9e-4966-8405-089bee5944ca',	'nom',	'Le nom de la personne',	'2025-02-24 18:13:46',	'2025-02-24 18:13:46'),
('6e8dc963-fb12-4dd0-ac1c-88f2bcc84216',	'prenom',	'Le prenom de la personne',	'2025-02-24 18:14:07',	'2025-02-24 18:14:07'),
('2b4de098-24e0-4863-9ac3-9cfd65af3bdd',	'telephone',	'Le telephone de la personne',	'2025-02-24 18:14:19',	'2025-02-24 18:14:19');

DROP TABLE IF EXISTS "champ_formulaire";
CREATE TABLE "public"."champ_formulaire" (
    "champ_id" uuid NOT NULL,
    "formulaire_id" uuid NOT NULL
) WITH (oids = false);

INSERT INTO "champ_formulaire" ("champ_id", "formulaire_id") VALUES
('0c049e3f-6b9e-4966-8405-089bee5944ca',	'c0c864e0-077e-4627-a658-abd2a48fa0b4'),
('6e8dc963-fb12-4dd0-ac1c-88f2bcc84216',	'c0c864e0-077e-4627-a658-abd2a48fa0b4'),
('2b4de098-24e0-4863-9ac3-9cfd65af3bdd',	'c0c864e0-077e-4627-a658-abd2a48fa0b4'),
('0c049e3f-6b9e-4966-8405-089bee5944ca',	'fa8fe09b-01f3-4c3f-ad79-7da0d97b0bf5');

DROP TABLE IF EXISTS "formulaire";
CREATE TABLE "public"."formulaire" (
    "id" uuid NOT NULL,
    "nom" character varying(100) NOT NULL,
    "description" text NOT NULL,
    "section_id" uuid NOT NULL,
    "created_at" timestamp NOT NULL,
    "updated_at" timestamp NOT NULL,
    CONSTRAINT "formulaire_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "formulaire" ("id", "nom", "description", "section_id", "created_at", "updated_at") VALUES
('c0c864e0-077e-4627-a658-abd2a48fa0b4',	'Formulaire boxe',	'C''est le formulaire de boxe de SLV',	'058a84a5-1cc8-428f-bf0f-00038241484f',	'2025-02-24 17:55:37',	'2025-02-24 18:05:18'),
('fa8fe09b-01f3-4c3f-ad79-7da0d97b0bf5',	'Formulaire de chant',	'C''est le formulaire de chant de SLV',	'd1382afe-dac8-4723-bd75-7c3b08295698',	'2025-02-25 09:51:14',	'2025-02-25 09:54:25');

DROP TABLE IF EXISTS "organismes";
CREATE TABLE "public"."organismes" (
    "id" uuid NOT NULL,
    "nom" character varying(100) NOT NULL,
    "description" text NOT NULL,
    "adresse" text NOT NULL,
    "created_at" timestamp NOT NULL,
    "updated_at" timestamp NOT NULL,
    CONSTRAINT "organismes_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "organismes" ("id", "nom", "description", "adresse", "created_at", "updated_at") VALUES
('a0c84a3b-a7dd-4d31-8f12-6a573ebe264d',	'Sport et Loisirs Vincéens',	'Association qui gère une quinzaine de sections sportives et de loisirs',	'123 rue Eugène Mouilleron',	'2025-12-02 16:43:44',	'2025-12-02 16:43:44'),
('08c708ed-f36f-4570-9a58-331209f0621d',	'IUT Charlemagne',	'Association de l''IUT Charlemagne',	'123 rue Eugène Mouilleron',	'2025-12-02 16:36:39',	'2025-12-02 16:36:39');

DROP TABLE IF EXISTS "paiement_partiel";
CREATE TABLE "public"."paiement_partiel" (
    "paiement_id" uuid NOT NULL,
    "montant" double precision NOT NULL,
    "date_paiement" timestamp NOT NULL,
    "mode_paiement" character varying NOT NULL,
    "id" uuid NOT NULL,
    CONSTRAINT "id" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "paiement_partiel" ("paiement_id", "montant", "date_paiement", "mode_paiement", "id") VALUES
('0e0ab11d-6def-4f63-93f4-42035d168155',	5,	'2025-02-25 13:17:53',	'espèces',	'198e7b80-6887-48fa-979b-4f5b14ee1ed4'),
('0e0ab11d-6def-4f63-93f4-42035d168155',	3,	'2025-02-25 13:54:45',	'espèces',	'65a2a28f-064a-4930-9ab6-97fa70a2ed0d'),
('0e0ab11d-6def-4f63-93f4-42035d168155',	2,	'2025-02-25 13:55:12',	'espèces',	'cd73a612-cb5b-4d3d-b7dc-1f369e9cc04d');

DROP TABLE IF EXISTS "paiements";
CREATE TABLE "public"."paiements" (
    "id" uuid NOT NULL,
    "montant_total" double precision NOT NULL,
    "reste_a_payer" double precision NOT NULL,
    "user_id" uuid NOT NULL,
    "section_id" uuid NOT NULL,
    "updated_at" timestamp NOT NULL,
    "status" character varying,
    CONSTRAINT "paiements_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "paiements" ("id", "montant_total", "reste_a_payer", "user_id", "section_id", "updated_at", "status") VALUES
('0e0ab11d-6def-4f63-93f4-42035d168155',	10,	0,	'11363c2b-cd17-4620-bee8-5c86b0c0515c',	'058a84a5-1cc8-428f-bf0f-00038241484f',	'2025-02-25 12:36:49',	'finalisé');

DROP TABLE IF EXISTS "sections";
CREATE TABLE "public"."sections" (
    "id" uuid NOT NULL,
    "nom" character varying(100) NOT NULL,
    "description" text NOT NULL,
    "categorie" character varying(100) NOT NULL,
    "capacite" integer NOT NULL,
    "tarif" double precision NOT NULL,
    "organisme_id" uuid NOT NULL,
    "created_at" timestamp NOT NULL,
    "updated_at" timestamp NOT NULL,
    CONSTRAINT "sections_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "sections" ("id", "nom", "description", "categorie", "capacite", "tarif", "organisme_id", "created_at", "updated_at") VALUES
('d1382afe-dac8-4723-bd75-7c3b08295698',	'Chant',	'Le chant de SLV',	'Musique',	15,	10,	'a0c84a3b-a7dd-4d31-8f12-6a573ebe264d',	'2025-02-18 23:26:21',	'2025-02-18 23:26:21'),
('058a84a5-1cc8-428f-bf0f-00038241484f',	'Boxe',	'La boxe de SLV',	'Sport',	10,	10,	'08c708ed-f36f-4570-9a58-331209f0621d',	'2025-02-17 18:02:10',	'2025-02-19 00:13:15'),
('e0dd5de4-e21a-4de1-8cff-777c61d4912b',	'Foot',	'Le foot de SLV',	'Sport',	10,	10,	'08c708ed-f36f-4570-9a58-331209f0621d',	'2025-02-19 00:08:49',	'2025-02-19 00:16:14'),
('9c4f9295-a3ab-4a66-875b-e4ec53805e02',	'Rugby',	'Le rugby de SLV',	'Sport',	15,	10,	'a0c84a3b-a7dd-4d31-8f12-6a573ebe264d',	'2025-02-19 00:42:42',	'2025-02-19 00:42:42'),
('93a0775a-f598-4623-9386-4955c0791bd4',	'Peinture SLV',	'La peinture de SLV',	'ART',	15,	10,	'a0c84a3b-a7dd-4d31-8f12-6a573ebe264d',	'2025-02-19 00:10:30',	'2025-03-31 22:05:34'),
('4533cb2f-2227-4e45-b29a-b4b4d3040f02',	'Dessin SLV',	'Le dessin de SLV',	'ART',	15,	10,	'a0c84a3b-a7dd-4d31-8f12-6a573ebe264d',	'2025-02-19 00:40:57',	'2025-03-31 22:14:00');

DROP TABLE IF EXISTS "user_section";
CREATE TABLE "public"."user_section" (
    "user_id" uuid NOT NULL,
    "section_id" uuid NOT NULL,
    "role" integer NOT NULL,
    "created_at" timestamp NOT NULL,
    "updated_at" timestamp NOT NULL
) WITH (oids = false);

INSERT INTO "user_section" ("user_id", "section_id", "role", "created_at", "updated_at") VALUES
('d94a69da-8ade-4f3e-ac5f-8695d5faf3cd',	'058a84a5-1cc8-428f-bf0f-00038241484f',	10,	'2025-02-18 23:24:29.48962',	'2025-02-18 23:24:29.48962'),
('d94a69da-8ade-4f3e-ac5f-8695d5faf3cd',	'd1382afe-dac8-4723-bd75-7c3b08295698',	10,	'2025-02-18 23:28:05.183523',	'2025-02-18 23:28:05.183523'),
('3ea9853e-fbf3-4fdc-9293-a8c9cc394953',	'e0dd5de4-e21a-4de1-8cff-777c61d4912b',	5,	'2025-02-19 00:07:03.409286',	'2025-02-19 00:07:03.409286'),
('d94a69da-8ade-4f3e-ac5f-8695d5faf3cd',	'4533cb2f-2227-4e45-b29a-b4b4d3040f02',	10,	'2025-02-19 00:40:57',	'2025-02-19 00:40:57'),
('120ca797-cbdd-460f-b433-f29f32896ed4',	'9c4f9295-a3ab-4a66-875b-e4ec53805e02',	15,	'2025-02-19 00:42:42',	'2025-02-19 00:42:42'),
('d94a69da-8ade-4f3e-ac5f-8695d5faf3cd',	'e0dd5de4-e21a-4de1-8cff-777c61d4912b',	0,	'2025-02-19 15:59:08',	'2025-02-19 15:59:08'),
('3ea9853e-fbf3-4fdc-9293-a8c9cc394953',	'058a84a5-1cc8-428f-bf0f-00038241484f',	5,	'2025-02-19 16:33:35',	'2025-02-19 16:33:35'),
('8a98f48f-c505-4bf3-b336-c70b20153ea0',	'd1382afe-dac8-4723-bd75-7c3b08295698',	5,	'2025-02-19 16:34:39',	'2025-02-19 16:34:39'),
('3ea9853e-fbf3-4fdc-9293-a8c9cc394953',	'd1382afe-dac8-4723-bd75-7c3b08295698',	0,	'2025-03-31 20:44:59',	'2025-03-31 20:44:59'),
('11363c2b-cd17-4620-bee8-5c86b0c0515c',	'93a0775a-f598-4623-9386-4955c0791bd4',	10,	'2025-03-31 21:01:56',	'2025-03-31 21:01:56');

DROP TABLE IF EXISTS "users";
CREATE TABLE "public"."users" (
    "id" uuid NOT NULL,
    "nom" character varying(100) NOT NULL,
    "prenom" character varying(100) NOT NULL,
    "mail" character varying(100) NOT NULL,
    "password" character varying(100) NOT NULL,
    "role" integer NOT NULL,
    "created_at" timestamp NOT NULL,
    "updated_at" timestamp NOT NULL,
    CONSTRAINT "users_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "users" ("id", "nom", "prenom", "mail", "password", "role", "created_at", "updated_at") VALUES
('120ca797-cbdd-460f-b433-f29f32896ed4',	'georges',	'victor',	'victor@test.com',	'$2y$10$N9POLDjCCC4XPCCmosXiPeMz5/DlEviQeKNNQl2z.qHQmX71i869W',	15,	'2025-12-02 13:33:54',	'2025-12-02 13:33:54'),
('11363c2b-cd17-4620-bee8-5c86b0c0515c',	'adherent',	'adherent',	'adherent@test.com',	'$2y$10$8ZYfJlG46F0QnQ4W3o5BYO4XOjeuircLk/lqys/pJVROIh4Fv4PN2',	0,	'2025-02-17 18:36:06',	'2025-02-17 18:36:06'),
('d94a69da-8ade-4f3e-ac5f-8695d5faf3cd',	'responsable',	'responsable',	'responsable@test.com',	'$2y$10$4FyKC55UMW2q8hLIt1pC9O0xGzsiLUa1wDY4pxbdIt2tKHmpenW0u',	10,	'2025-02-17 18:35:33',	'2025-02-17 18:35:33'),
('3ea9853e-fbf3-4fdc-9293-a8c9cc394953',	'encadrant',	'encadrant',	'encadrant@test.com',	'$2y$10$jgfLvSEJ0g/he1s718cCw.G4nlPnTQ/1y2OTaIXNBnARSsDrhGayG',	5,	'2025-02-17 18:35:50',	'2025-02-17 18:35:50'),
('8a98f48f-c505-4bf3-b336-c70b20153ea0',	'encadrant2',	'encadrant2',	'encadrant2@test.com',	'$2y$10$SCLlqKqqh6oXF/Ae5hf37eDb4BGE1AH6KiEVxnRGGgbSraf/dMaIC',	0,	'2025-02-19 16:34:06',	'2025-02-19 16:34:06'),
('fd8314b7-ccab-4661-b2ec-eb184b7a2402',	'responsable2',	'responsable2',	'responsable2@test.com',	'$2y$10$tVGLAZt3lt6naiZXJasPyOXiBQV3RswGGwYKisTN.cWQQTiu/3ErG',	10,	'2025-02-19 17:26:44',	'2025-02-19 17:26:44');

ALTER TABLE ONLY "public"."champ_formulaire" ADD CONSTRAINT "champ_formulaire_champ_id_fkey" FOREIGN KEY (champ_id) REFERENCES champ(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."champ_formulaire" ADD CONSTRAINT "champ_formulaire_formulaire_id_fkey" FOREIGN KEY (formulaire_id) REFERENCES formulaire(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "public"."formulaire" ADD CONSTRAINT "formulaire_section_id_fkey" FOREIGN KEY (section_id) REFERENCES sections(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "public"."paiement_partiel" ADD CONSTRAINT "paiement_section_paiement_id_fkey" FOREIGN KEY (paiement_id) REFERENCES paiements(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "public"."paiements" ADD CONSTRAINT "paiements_section_id_fkey" FOREIGN KEY (section_id) REFERENCES sections(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."paiements" ADD CONSTRAINT "paiements_user_id_fkey" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "public"."sections" ADD CONSTRAINT "sections_organisme_id_fkey" FOREIGN KEY (organisme_id) REFERENCES organismes(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "public"."user_section" ADD CONSTRAINT "user_section_section_id_fkey" FOREIGN KEY (section_id) REFERENCES sections(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."user_section" ADD CONSTRAINT "user_section_user_id_fkey" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;

-- 2025-03-31 22:24:23.795525+00
