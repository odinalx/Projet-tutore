<?php
namespace slv\core\domain\entities\paiement;

use slv\core\domain\entities\Entity;
use Ramsey\Uuid\Guid\Guid;

class Paiement extends Entity {

    protected float $montant_total;
    protected float $reste_a_payer;
    protected string $status;
    protected string $user_id;
    protected string $section_id;
    protected \DateTimeImmutable $updated_at;

    public function __construct(string $montant_total, string $reste_a_payer, string $user_id, string $section_id){
        $this->setID(Guid::uuid4()->toString());
        $this->montant_total = $montant_total;
        $this->reste_a_payer = $reste_a_payer;
        $this->status = "En attente";
        $this->user_id = $user_id;
        $this->section_id = $section_id;
        $this->updated_at = new \DateTimeImmutable();      
    } 

}