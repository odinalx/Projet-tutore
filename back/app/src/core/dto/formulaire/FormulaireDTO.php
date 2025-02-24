<?php
namespace slv\core\dto\formulaire;

use slv\core\dto\DTO;

class FormulaireDTO extends DTO {
    protected string $id;
    protected string $nom;
    protected string $description;
    protected string $section_id;
    protected string $created_at;
    protected string $updated_at;
    protected array $champs;

    public function __construct(string $id, string $nom, string $description, string $section_id, string $created_at, string $updated_at, array $champs = []) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->section_id = $section_id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->champs = $champs;
    }
}
