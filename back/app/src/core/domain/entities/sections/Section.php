<?php

namespace slv\core\domain\entities\sections;

use slv\core\domain\entities\Entity;
use Ramsey\Uuid\Guid\Guid;

class Section extends Entity {
    protected string $nom;
    protected string $description;
    protected string $categorie;
    protected int $capacite;
    protected float $tarif;
    protected string $organisme_id;
    protected \DateTimeImmutable $created_at;
    protected \DateTimeImmutable $updated_at;

    public function __construct(string $nom, string $description, string $categorie, int $capacite, float $tarif, string $organisme_id) {
        $this->setID(Guid::uuid4()->toString());
        $this->nom = $nom;
        $this->description = $description;
        $this->categorie = $categorie;
        $this->capacite = $capacite;
        $this->tarif = $tarif;
        $this->organisme_id = $organisme_id;
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }
    
}