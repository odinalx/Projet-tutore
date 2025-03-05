<?php
namespace slv\core\dto\sections;

use slv\core\dto\DTO;

class SectionDTO extends DTO {
    protected string $id;
    protected string $nom;
    protected string $description;
    protected string $categorie;
    protected int $capacite;
    protected float $tarif;
    protected string $organisme_id;
    protected ?int $role;
    protected string $created_at;
    protected string $updated_at;

    public function __construct(string $id, string $nom, string $description, string $categorie, int $capacite, float $tarif, string $organisme_id, string $created_at, string $updated_at, ?int $role = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->categorie = $categorie;
        $this->capacite = $capacite;
        $this->tarif = $tarif;
        $this->organisme_id = $organisme_id;
        $this->role = $role;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    
}
