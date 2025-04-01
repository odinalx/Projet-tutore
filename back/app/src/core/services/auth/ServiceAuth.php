<?php

namespace slv\core\services\auth;

use app\providers\JWTManager;
use slv\core\domain\entities\user\User;
use slv\core\dto\auth\CredentialsDTO;
use slv\core\repositoryInterfaces\auth\AuthRepositoryInterface;
use slv\infrastructure\PDO\auth\PdoAuthException;
use slv\core\dto\auth\UserDTO;

class ServiceAuth implements ServiceAuthInterface
{
    private AuthRepositoryInterface $authRepository;
    private JWTManager $jwtManager;

    public function __construct(AuthRepositoryInterface $authRepository, JWTManager $jwtManager)
    {
        $this->authRepository = $authRepository;
        $this->jwtManager = $jwtManager;
    }

    /**
     * Méthode pour enregistrer un utilisateur
     */
    public function register(CredentialsDTO $credentials, string $nom, string $prenom): void
    {
        try {
            $user = new User($nom, $prenom, $credentials->email, $credentials->password);
            $this->authRepository->register($user);
        } catch (PdoAuthException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    /**
     * Méthode pour authentifier un utilisateur
     */
    public function login(CredentialsDTO $credentials): UserDTO
    {
        try {
            $userDTO = $this->authRepository->login($credentials->email);
            $userDTO->validate();

            if (!$userDTO || !password_verify($credentials->password, $userDTO->hashed_password)) {
                throw new AuthenticationException('Identifiants invalides');
            }

            $accessToken = $this->jwtManager->createAccessToken([
                'id' => $userDTO->id,
                'email' => $userDTO->email,
                'exp' => time() + 3600, // Access token valable 1 heure
                'role' => $userDTO->role,
            ]);

            $refreshToken = $this->jwtManager->createRefreshToken([
                'id' => $userDTO->id,
                'email' => $userDTO->email,
                'exp' => time() + 86400, // Refresh token valable 24 heures
                'role' => $userDTO->role,
            ]);

            $userDTO->setAccessToken($accessToken);
            $userDTO->setRefreshToken($refreshToken);
            return $userDTO;
        } catch (PdoAuthException | \Exception $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    /**
     * Méthode pour rafraichir un token
     */
    public function refresh(string $refreshToken): UserDTO
    {
        try {
            $decodedToken = $this->jwtManager->decodeToken($refreshToken);

            $userId = $decodedToken['id'];
            $userDto = $this->authRepository->getUserById($userId);

            $newAccessToken = $this->jwtManager->createAccessToken([
                'id' => $userDto->id,
                'email' => $userDto->email,
                'exp' => time() + 3600,
                'role' => $userDto->role,
            ]);

            $userDto->setAccessToken($newAccessToken);
            return $userDto;
        } catch (PdoAuthException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }
}
