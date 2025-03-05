<?php
namespace slv\core\domain\entities\paiement;

use slv\core\domain\entities\Entity;
use Ramsey\Uuid\Guid\Guid;

class PaiementPartiel extends Entity {

    protected string $paiement_id;
    protected float $montant;
    protected string $mode_paiement;
    protected \DateTimeImmutable $date_paiement;

    public function __construct(string $paiement_id, float $montant, string $mode_paiement){
        $this->setID(Guid::uuid4()->toString());
        $this->paiement_id = $paiement_id;
        $this->montant = $montant;
        $this->mode_paiement = $mode_paiement;
        $this->date_paiement = new \DateTimeImmutable();      
    } 

}