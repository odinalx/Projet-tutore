<?php
namespace slv\core\dto\organisme;

use slv\core\dto\DTO;

class OrganismeDTO extends DTO {
    protected string $id;
    protected string $nom;
    protected string $description;
    protected string $adresse;
    protected string $created_at;
    protected string $updated_at;

    public function __construct(string $id, string $nom, string $description, string $adresse, string $created_at, string $updated_at) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->adresse = $adresse;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): void{
        $this->updated_at = $updated_at;
    }
}
