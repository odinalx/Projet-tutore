<?php

namespace slv\core\domain\entities\formulaire;

use slv\core\domain\entities\Entity;
use Ramsey\Uuid\Guid\Guid;

class Formulaire extends Entity {
    protected string $nom;
    protected string $description;
    protected string $section_id;
    protected \DateTimeImmutable $created_at;
    protected \DateTimeImmutable $updated_at;

    public function __construct(string $nom, string $description, string $section_id) {
        $this->setID(Guid::uuid4()->toString());
        $this->nom = $nom;
        $this->description = $description;
        $this->section_id = $section_id;
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }
    
}