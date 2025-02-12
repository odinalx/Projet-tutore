<?php

namespace slv\core\services\authorization;

use slv\core\domain\entities\user\User;
use slv\core\repositoryInterfaces\auth\AuthRepositoryInterface;

class AuthrzService implements AuthrzServiceInterface
{

    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function isGrantedOrganisme(string $userId): bool
    {   
        $userDTO = $this->authRepository->getUserById($userId);
        if ($userDTO->role !== User::ROLE_ADMIN) {
            throw new AuthrzInvalidRoleException("Vous n'avez pas les droits pour effectuer cette action.");
        }

        return true;
    }
}
