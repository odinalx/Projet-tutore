<?php

namespace slv\core\services\auth;

use slv\core\dto\auth\UserDTO;
use slv\core\dto\auth\CredentialsDTO;

interface ServiceAuthInterface
{   
    public function register(CredentialsDTO $credentials, string $nom, string $prenom): void; // Methode pour enregistrer un utilisateur
    public function login(CredentialsDTO $credentials): UserDTO; // Methode pour authentifier un utilisateur
    public function refresh(string $refreshToken): UserDTO; // Methode pour rafraichir un token
}