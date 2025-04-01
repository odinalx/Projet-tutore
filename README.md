# Projet-tutore SLV App

Le projet consiste à développer une application web pour l'association Sports et Loisirs Vincéens (SLV), qui gère une quinzaine de sections sportives et de loisirs. L'objectif principal est de moderniser et centraliser les opérations de gestion des sections, rôles et adhérents au sein de l’association. 

## Auteurs
- GEORGES Victor
- ALEXANDRE Odin
- MARTINEZ ORTUÑO Galo Eduardo
- Muñoz Ramírez José Guadalupe

## Installation et Setup

### Prérequis
- Docker

### Installation
1. Cloner le repo :  
   ```bash
   git clone https://github.com/odinalx/Projet-tutore.git
   cd Projet-tutore
   ```

2. Ajouter les fichiers d'environements :

- *slv.db.ini* dans "back/app/config" en suivant l'exemple *slv.db.ini.example*
- *slv.env* dans à la racine de "back" en suivant cet exemple :
```.env
POSTGRES_DB=slvdb
POSTGRES_USER=
POSTGRES_PASSWORD=
```
L'user et le password doivent être les mêmes dans les deux fichiers d'environnements

- *.env* dans à la racine de "front" en suivant *.env.example*. Pour lancer en local, l'url de l'api est **http://localhost:9000**

3. Lancer les conteneurs à la racine
 ```bash
 docker compose up --build
 ```

4. L'application sera disponible à l'adresse : **http://localhost:8080/**

## Technologies utilisées
- **Frontend** : VueJs, TailwindCSS
- **Backend** : PHP, Slim
- **Base de données** : PostGres


## Fonctionnalités réalisées
- [x] Authentification avec login et register 
### Organismes
- [x] Récupération de tous les organismes (Authentification ❌​, Autorisation ❌)
- [x] Récupération d'un organisme (Authentification ❌, Autorisation ❌)
### Sections
- [x] Récupération de toutes les sections d'un organisme (Authentification ❌​, Autorisation ❌)
- [x] Récupération d'une section (Authentification ❌, Autorisation ❌)
- [x] Création d'une section (Authentification ✅​​, Autorisation ✅​)
- [x] Supression d'une section (Authentification ✅​​, Autorisation ✅​)
- [x] Modification d'une section (Authentification ✅​​, Autorisation ✅​)
- [x] Récupération des sections d'un utilisateur (Authentification ✅​​, Autorisation ❌ ​)
- [x] Associer (inscription) un utilisateur à une section avec son rôle (Authentification ✅​​, Autorisation ❌​)
- [x] Récupérer le rôle d'un utilisateur selon sa section associée (Authentification ✅​​, Autorisation ❌ ​)
### Encadrants
- [x] Récupérer les encadrants d'un responsable (Authentification ✅​​, Autorisation ✅​)
- [x] Supprimer un encadrant d'une section (Authentification ✅​​, Autorisation ✅​)


## Fonctionnalités en attente et à intégrer au frontend
- [ ] Refresh token
### Organismes
- [ ] Créer un nouvel organisme (Authentification ✅​​, Autorisation ✅​)
- [ ] Supprimer un organisme (Authentification ✅​​, Autorisation ✅​)
- [ ] Modifier un organisme (Authentification ✅​​, Autorisation ✅​)
### Formulaires
- [ ] Création d'un nouveau formulaire par le responsable d'une section (Authentification ✅​​, Autorisation ✅​)
- [ ] Récupération du formulaire d'une section (Authentification ✅​​, Autorisation ❌ ​)
- [ ] Supprimer un formulaire (Authentification ✅​​, Autorisation ✅​)
- [ ] Modification d'un formulaire (Authentification ✅​​, Autorisation ✅​)
- [ ] Associer un champ à un formualire (Authentification ✅​​, Autorisation ✅​)
### Champs
- [ ] Création d'un nouveau champ de formulaire (Authentification ✅​​, Autorisation ✅​)
- [ ] Récupération d'un champ (Authentification ✅​​, Autorisation ❌ ​)
- [ ] Supression d'un champ (Authentification ✅​​, Autorisation ✅​)
### Paiements
- [ ] Générer un nouveau paiement (Authentification ✅​​, Autorisation ✅​)
- [ ] Récupération d'un paiement (Authentification ✅​​, Autorisation ✅​)
- [ ] Supprimer un paiement (Authentification ✅​​, Autorisation ✅​)
- [ ] Créer un paiement partiel pour pouvoir payer en plusieurs fois (Authentification ✅​​, Autorisation ✅​)
### Lieu
- [ ] Création d'un nouveau lieu (Authentification ✅​​, Autorisation ✅​)
- [ ] Récupération d'un lieu (Authentification ✅​​, Autorisation ❌ ​)
- [ ] Supression d'un lieu (Authentification ✅​​, Autorisation ✅​)
- [ ] Modification d'un lieu (Authentification ✅​​, Autorisation ✅​)
### Activités
- [ ] Création d'une nouvelle activité (Authentification ✅​​, Autorisation ✅​)
- [ ] Récupération d'une activité (Authentification ✅​​, Autorisation ❌ ​)
- [ ] Supression d'une activité (Authentification ✅​​, Autorisation ✅​)
- [ ] Modification d'une activité (Authentification ✅​​, Autorisation ✅​) 

## Fonctionnalités à faire
- [ ] Demande d'inscription à une section par un utilisateur
- [ ] Cette demande d'inscription se fait sur une page dédiée avec le formulaire d'une section et ses champs
- [ ] Récupération des demandes d'inscriptions à une section par un responsable/encadrant afin de l'inscrire défintitivement
- [ ] Systeme de planning avec les activités selon un utilisateur
- [ ] Affichage des informations utilisateur dans son profil selon les champs qu'il a rempli dans un/des formulaire(s) (formulaire_rempli et reponse_champ)
- [ ] Ajouter des images pour chaque section
- [ ] Rajouter un historique de paiements dans le profil utilisateur
- [ ] Moyen plus simple d'exporter un dumb de la base de données


## Liens
- [Présentation projet CMS](https://webetu.iutnc.univ-lorraine.fr/www/george264u/projet-tutore/)
- [Projet Github](https://github.com/odinalx/Projet-tutore)

