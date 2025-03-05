<?php
namespace slv\core\dto\paiement;

use slv\core\dto\DTO;

class PaiementDTO extends DTO {
    protected string $id;
    protected string $status;
    protected float $montant_total;
    protected float $reste_a_payer;
    protected string $user_id;
    protected string $section_id;
    protected string $updated_at;
    protected array $paiements_partiels;

    public function __construct(string $id, string $status, float $montant_total, float $reste_a_payer, string $user_id, string $section_id, string $updated_at, array $paiements_partiels = []) {
        $this->id = $id;
        $this->status = $status;
        $this->montant_total = $montant_total;
        $this->reste_a_payer = $reste_a_payer;
        $this->user_id = $user_id;
        $this->section_id = $section_id;
        $this->updated_at = $updated_at;
        $this->paiements_partiels = $paiements_partiels;
    }

    
}
