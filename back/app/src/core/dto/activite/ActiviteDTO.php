<?php

namespace slv\core\dto\activite;

use slv\core\dto\DTO;

class ActiviteDTO extends DTO {
    protected string $id;
    protected string $nom;
    protected ?string $description;
    protected string $sections_id;
    protected string $lieu_id;
    protected string $date_debut;
    protected string $date_fin;
    protected string $created_at;
    protected string $updated_at;

    public function __construct(
        string $id,
        string $nom,
        ?string $description,
        string $sections_id,
        string $lieu_id,
        string $date_debut,
        string $date_fin,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->sections_id = $sections_id;
        $this->lieu_id = $lieu_id;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
