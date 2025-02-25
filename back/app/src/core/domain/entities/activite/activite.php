<?php

namespace slv\core\domain\entities\activite;

use slv\core\domain\entities\Entity;
use Ramsey\Uuid\Uuid;

class Activite extends Entity {
    protected string $nom;
    protected ?string $description;
    protected string $sections_id;
    protected string $lieu_id;
    protected \DateTimeImmutable $date_debut;
    protected \DateTimeImmutable $date_fin;
    protected \DateTimeImmutable $created_at;
    protected \DateTimeImmutable $updated_at;

    public function __construct(
        string $nom,
        ?string $description,
        string $sections_id,
        string $lieu_id,
        \DateTimeImmutable $date_debut,
        \DateTimeImmutable $date_fin
    ) {
        $this->setID(Uuid::uuid4()->toString());
        $this->nom = $nom;
        $this->description = $description;
        $this->sections_id = $sections_id;
        $this->lieu_id = $lieu_id;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }
    
    public function getID(): ?string
    {
        return $this->ID;
    }

}
