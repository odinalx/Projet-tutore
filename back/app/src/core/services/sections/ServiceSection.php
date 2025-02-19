<?php

namespace slv\core\services\sections;

use slv\core\domain\entities\sections\Section;
use slv\core\dto\sections\SectionDTO;
use slv\core\repositoryInterfaces\sections\SectionRepositoryInterface;
use slv\infrastructure\PDO\sections\PdoSectionException;

class ServiceSection implements ServiceSectionInterface
{
    private SectionRepositoryInterface $sectionRepository;

    public function __construct(SectionRepositoryInterface $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function createSection(string $userid, string $nom, string $description, string $categorie, int $capacite, float $tarif, string $organisme_id): void
    {
        try {
            $section = new Section($nom, $description, $categorie, $capacite, $tarif, $organisme_id);
            $this->sectionRepository->createSection($section, $userid);
        } catch (PdoSectionException $e) {
            throw new ServiceSectionException($e->getMessage());
        }
    }

    public function getSection(string $id): SectionDTO
    {
        try {
            return $this->sectionRepository->getSection($id);
        } catch (PdoSectionException $e) {
            throw new ServiceSectionException($e->getMessage());
        }
    }

    public function deleteSection(string $id): void
    {
        try {
            $this->sectionRepository->deleteSection($id);
        } catch (PdoSectionException $e) {
            throw new ServiceSectionException($e->getMessage());
        }
    }

    public function updateSection(string $id, ?string $nom, ?string $description, ?string $categorie, ?int $capacite, ?float $tarif, ?string $organisme_id): SectionDTO
    {
        try {
            return $this->sectionRepository->updateSection($id, $nom, $description, $categorie, $capacite, $tarif, $organisme_id);
        } catch (PdoSectionException $e) {
            throw new ServiceSectionException($e->getMessage());
        }
    }

    public function getSectionsByUser(string $user_id): array
    {
        try {
            return $this->sectionRepository->getSectionsByUser($user_id);
        } catch (PdoSectionException $e) {
            throw new ServiceSectionException($e->getMessage());
        }
    }
}
