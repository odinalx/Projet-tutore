<?php

namespace slv\core\dto\lieu;

use slv\core\dto\DTO;

class LieuDTO extends DTO {
    protected string $id;
    protected string $nom;
    protected string $adresse;
    protected string $ville;
    protected string $code_postal;
    protected string $created_at;
    protected string $updated_at;

    public function __construct(
        string $id,
        string $nom,
        string $adresse,
        string $ville,
        string $code_postal,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->code_postal = $code_postal;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
