<?php
namespace slv\core\dto\encadrants;

use slv\core\dto\DTO;

class EncadrantDTO extends DTO {
    protected string $id;
    protected string $nom;
    protected string $prenom;
    protected string $email;
    protected string $role;
    protected string $section_id;

    public function __construct(string $id, string $nom, string $prenom, string $email, string $role, string $section_id) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->role = $role;
        $this->section_id = $section_id;
    }
}
