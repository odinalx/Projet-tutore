<?php
namespace slv\core\services\paiement;

use slv\core\dto\paiement\PaiementDTO;

interface ServicePaiementInterface {

    public function createPaiement(string $montant_total, string $reste_a_payer, string $user_id, string $section_id): void;
    public function getPaiement(string $id): PaiementDTO;
    public function deletePaiement(string $id): void;

    public function createPaiementPartiel(string $paiement_id, float $montant, string $mode_paiement): void;
}