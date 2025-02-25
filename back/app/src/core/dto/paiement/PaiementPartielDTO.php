<?php
namespace slv\core\dto\paiement;

use slv\core\dto\DTO;

class PaiementPartielDTO extends DTO {
    protected string $id;
    protected string $paiement_id;
    protected float $montant;
    protected string $date_paiement;
    protected string $mode_paiement;
    

    public function __construct(string $id, string $paiement_id, float $montant, string $date_paiement, string $mode_paiement) {
        $this->id = $id;
        $this->paiement_id = $paiement_id;
        $this->montant = $montant;
        $this->date_paiement = $date_paiement;
        $this->mode_paiement = $mode_paiement;
        
    }

    
}
