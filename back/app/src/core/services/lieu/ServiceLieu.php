<?php

namespace slv\core\services\lieu;

use slv\core\domain\entities\lieu\Lieu;
use slv\core\dto\lieu\LieuDTO;
use slv\core\repositoryInterfaces\lieu\LieuRepositoryInterface;
use slv\infrastructure\PDO\lieu\PdoLieuException;
use slv\core\services\lieu\ServiceLieuInterface;

class ServiceLieu implements ServiceLieuInterface
{
    private LieuRepositoryInterface $lieuRepository;

    public function __construct(LieuRepositoryInterface $lieuRepository)
    {
        $this->lieuRepository = $lieuRepository;
    }

    public function CreateLieu(?string $nom, ?string $adresse, ?string $ville, ?string $code_postal): void
    {
        try {
            $lieu = new Lieu($nom, $adresse, $ville, $code_postal);
            $this->lieuRepository->createLieu($lieu);
        } catch (\Exception $e) {
            throw new ServiceLieuException("Erreur lors de la création du lieu : " . $e->getMessage());
        }
    }

    public function DeleteLieu(string $id): void
    {
        try {
            $this->lieuRepository->DeleteLieu($id);
        } catch (PdoLieuException $e) {
            throw new ServiceLieuException($e->getMessage());
        }
    }

    public function UpdateLieu(?string $id, ?string $nom, ?string $adresse, ?string $ville, ?string $code_postal): LieuDTO
    {
        try {
            return $this->lieuRepository->updateLieu($id, $nom, $adresse, $ville, $code_postal);
        } catch (PdoLieuException $e) {
            throw new ServiceLieuException($e->getMessage());
        }
    }

    public function getLieu(string $id): LieuDTO
    {
        try {
            return $this->lieuRepository->getLieu($id);
        } catch (PdoLieuException $e) {
            throw new ServiceLieuException($e->getMessage());
        }
    }
}
