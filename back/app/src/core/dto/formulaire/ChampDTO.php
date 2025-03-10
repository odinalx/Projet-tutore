<?php
namespace slv\core\dto\formulaire;

use slv\core\dto\DTO;

class ChampDTO extends DTO {
    protected string $id;
    protected string $nom;
    protected string $description;
    protected string $created_at;
    protected string $updated_at;

    public function __construct(string $id, string $nom, string $description, string $created_at, string $updated_at) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
