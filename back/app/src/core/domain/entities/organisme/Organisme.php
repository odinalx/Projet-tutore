<?php

namespace slv\core\domain\entities\organisme;

use slv\core\domain\entities\Entity;
use Ramsey\Uuid\Guid\Guid;

class Organisme extends Entity {
    protected string $nom;
    protected string $description;
    protected string $adresse;
    protected \DateTimeImmutable $created_at;
    protected \DateTimeImmutable $updated_at;

    public function __construct(string $nom, string $description, string $adresse) {
        $this->setID(Guid::uuid4()->toString());
        $this->nom = $nom;
        $this->description = $description;
        $this->adresse = $adresse;
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): void {
        $this->updated_at = $updated_at;
    }
    
}