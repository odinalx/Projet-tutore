<?php

namespace slv\core\services\activite;

use slv\core\domain\entities\activite\Activite;
use slv\core\dto\activite\ActiviteDTO;
use slv\core\repositoryInterfaces\activite\ActiviteRepositoryInterface;
use slv\infrastructure\PDO\activite\PdoActiviteException;

class ServiceActivite implements ServiceActiviteInterface
{
    private ActiviteRepositoryInterface $activiteRepository;

    public function __construct(ActiviteRepositoryInterface $activiteRepository)
    {
        $this->activiteRepository = $activiteRepository;
    }

    public function createActivite(string $nom, ?string $description, string $sections_id, string $lieu_id, string $date_debut, string $date_fin): void
    {
        try {
            $dateDebut = new \DateTimeImmutable($date_debut);
            $dateFin = new \DateTimeImmutable($date_fin);
    
            $activite = new Activite($nom, $description, $sections_id, $lieu_id, $dateDebut, $dateFin);
            $this->activiteRepository->createActivite($activite);
        } catch (\Exception $e) {
            throw new ServiceActiviteException("Erreur lors de la création de l'activité : " . $e->getMessage());
        }
    }
    

    public function DeleteActivite(string $id): void
    {
        try {
            $this->activiteRepository->DeleteActivite($id);
        } catch (PdoActiviteException $e) {
            throw new ServiceActiviteException($e->getMessage());
        }
    }

    public function updateActivite(string $id, ?string $nom, ?string $description, ?string $sections_id, ?string $lieu_id): ActiviteDTO
    {
        try {
            return $this->activiteRepository->updateActivite($id, $nom, $description, $sections_id, $lieu_id);
        } catch (PdoActiviteException $e) {
            throw new ServiceActiviteException($e->getMessage());
        }
    }

    public function getActivite(string $id): ActiviteDTO
    {
        try {
            return $this->activiteRepository->getActivite($id);
        } catch (PdoActiviteException $e) {
            throw new ServiceActiviteException($e->getMessage());
        }
    }
}
