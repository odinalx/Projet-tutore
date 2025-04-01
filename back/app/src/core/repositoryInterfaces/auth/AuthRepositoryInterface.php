<?php

namespace slv\core\repositoryInterfaces\auth;

use slv\core\domain\entities\user\User;
use slv\core\dto\auth\UserDTO;

interface AuthRepositoryInterface {
    public function register (User $user): void; // enregistre un utilisateur
    public function login(string $email): ?UserDTO; // Authentifie un utilisateur par son email
    public function getUserById(string $id): ?UserDTO; // Récupère un utilisateur par son id
}