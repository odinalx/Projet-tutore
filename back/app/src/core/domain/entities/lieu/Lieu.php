<?php

namespace slv\core\domain\entities\lieu;

use slv\core\domain\entities\Entity;
use Ramsey\Uuid\Uuid;

class Lieu extends Entity {
    protected string $nom;
    protected string $adresse;
    protected string $ville;
    protected string $code_postal;
    protected \DateTimeImmutable $created_at;
    protected \DateTimeImmutable $updated_at;

    public function __construct(string $nom, string $adresse, string $ville, string $code_postal) {
        $this->setID(Uuid::uuid4()->toString());
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->code_postal = $code_postal;
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

}
