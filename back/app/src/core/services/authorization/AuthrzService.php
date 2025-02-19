<?php

namespace slv\core\services\authorization;

use slv\core\domain\entities\user\User;
use slv\core\repositoryInterfaces\auth\AuthRepositoryInterface;
use slv\core\repositoryInterfaces\sections\SectionRepositoryInterface;

class AuthrzService implements AuthrzServiceInterface
{

    private AuthRepositoryInterface $authRepository;
    private SectionRepositoryInterface $sectionRepository;

    public function __construct(AuthRepositoryInterface $authRepository, SectionRepositoryInterface $sectionRepository)
    {
        $this->authRepository = $authRepository;
        $this->sectionRepository = $sectionRepository;
    }

    public function isGrantedOrganisme(string $userId): bool
    {
        $userDTO = $this->authRepository->getUserById($userId);
        if ($userDTO->role !== User::ROLE_ADMIN) {
            throw new AuthrzInvalidRoleException("Vous n'avez pas les droits pour effectuer cette action.");
        }

        return true;
    }

    public function isGrantedCreateSection(string $userId): bool
    {
        $userDTO = $this->authRepository->getUserById($userId);

        if (!in_array($userDTO->role, [User::ROLE_ADMIN, User::ROLE_RESPONSABLE])) {
            throw new AuthrzInvalidRoleException("Vous n'avez pas les droits pour créer une section.");
        }

        return true;
    }


    public function isGrantedSection(string $userId, string $sectionId): bool
    {
        $userDTO = $this->authRepository->getUserById($userId);

        // Si l'utilisateur est admin, il a tous les droits
        if ($userDTO->role === User::ROLE_ADMIN) {
            return true;
        }

        // Si l'utilisateur est responsable, vérifier qu'il est bien affecté à cette section
        if ($userDTO->role === User::ROLE_RESPONSABLE) {
            $sections = $this->sectionRepository->getSectionsByUser($userId);

            // Vérifier si la section donnée est bien dans la liste des sections du user
            foreach ($sections as $section) {
                if ($section->id === $sectionId) {
                    return true;
                }
            }

            throw new AuthrzNotOwnerException("Vous n'êtes pas responsable de cette section.");
        }

        // Si l'utilisateur n'est ni admin ni responsable, il est interdit d'accès
        throw new AuthrzInvalidRoleException("Vous n'avez pas les droits pour effectuer cette action.");
    }
}
