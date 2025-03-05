<?php
namespace slv\core\repositoryInterfaces\paiement;

use slv\core\domain\entities\paiement\Paiement;
use slv\core\domain\entities\paiement\PaiementPartiel;
use slv\core\dto\paiement\PaiementDTO;

interface PaiementRepositoryInterface {
    public function createPaiement(Paiement $paiement): void;
    public function getPaiement(string $id): PaiementDTO;
    public function getPaiementsPartielsByPaiement(string $id): array;
    public function deletePaiement(string $id): void;

    public function createPaiementPartiel(PaiementPartiel $paiementPartiel): void;
}