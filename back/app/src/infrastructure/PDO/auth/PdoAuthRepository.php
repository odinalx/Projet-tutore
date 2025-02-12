<?php

namespace slv\infrastructure\PDO\auth;

use PDO;
use slv\core\repositoryInterfaces\auth\AuthRepositoryInterface;
use slv\core\domain\entities\user\User;
use slv\core\dto\auth\UserDTO;

class PdoAuthRepository implements AuthRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Enregistre un utilisateur
     * @param User $user
     */
    public function register(User $user): void
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO users (id, nom, prenom, mail, password, role, created_at, updated_at) VALUES (:id, :nom, :prenom, :email, :password, :role, :created_at, :updated_at)');
            $stmt->execute([
                'id' => $user->getID(),
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'password' => password_hash($user->password, PASSWORD_DEFAULT),
                'role' => $user->role,
                'created_at' => $user->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $user->updated_at->format('d-m-Y H:i:s')
            ]);
        } catch (\Exception $e) {
            throw new PdoAuthException('Erreur lors de l\'enregistrement de l\'utilisateur : ' . $e->getMessage());
        }
    }

    /**
     * Authentifie un utilisateur par son email
     * @param string $email
     * @return UserDTO|null
     */
    public function login(string $email): ?UserDTO
    {
        try {
            $stmt = $this->pdo->prepare('SELECT id, mail, password FROM users WHERE mail = :mail');
            $stmt->execute(['mail' => $email]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new UserDTO($row['id'],$row['mail'], $row['password']);
            }
            return null;
        } catch (\Exception $e) {
            throw new PdoAuthException('Erreur lors de la connexion de l\'utilisateur : ' . $e->getMessage());
        }
    }

    /**
     * Récupère un utilisateur par son id
     * @param string $id
     * @return UserDTO|null
     */
    public function getUserById(string $id): ?UserDTO
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new UserDTO($row['id'], $row['mail'], $row['password']);
            }
            return null;
        } catch (\Exception $e) {
            throw new PdoAuthException('Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage());
        }
    }
}
