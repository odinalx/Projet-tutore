<?php
namespace slv\core\services\paiement;

use slv\core\repositoryInterfaces\paiement\PaiementRepositoryInterface;
use slv\infrastructure\PDO\paiement\PdoPaiementException;
use slv\core\domain\entities\paiement\Paiement;
use slv\core\domain\entities\paiement\PaiementPartiel;
use slv\core\dto\paiement\PaiementDTO;

class ServicePaiement implements ServicePaiementInterface
{
    private PaiementRepositoryInterface $paiementRepository;

    public function __construct(PaiementRepositoryInterface $paiementRepository)
    {
        $this->paiementRepository = $paiementRepository;
    }

    public function createPaiement(string $montant_total, string $reste_a_payer, string $user_id, string $section_id): void
    {   
        try{
            $paiement = new Paiement($montant_total, $reste_a_payer, $user_id, $section_id);
            $this->paiementRepository->createPaiement($paiement);
        } catch (PdoPaiementException $e) {
            throw new ServicePaiementException($e->getMessage());
        }
    }

    public function getPaiement(string $id): PaiementDTO
    {
        try {
            return $this->paiementRepository->getPaiement($id);
        } catch (PdoPaiementException $e) {
            throw new ServicePaiementException($e->getMessage());
        }
    }

    public function deletePaiement(string $id): void
    {
        try {
            $this->paiementRepository->deletePaiement($id);
        } catch (PdoPaiementException $e) {
            throw new ServicePaiementException($e->getMessage());
        }
    }

    public function createPaiementPartiel(string $paiement_id, float $montant, string $mode_paiement): void
    {
        try {
            $paiementPartiel = new PaiementPartiel($paiement_id, $montant, $mode_paiement);
            $this->paiementRepository->createPaiementPartiel($paiementPartiel);
        } catch (PdoPaiementException $e) {
            throw new ServicePaiementException($e->getMessage());
        }
    }

    
}