<?php
namespace slv\core\repositoryInterfaces\activite;

use slv\core\domain\entities\activite\Activite;
use slv\core\dto\activite\ActiviteDTO;


interface ActiviteRepositoryInterface {

    public function CreateActivite(Activite $activite):void;
    public function DeleteActivite(string $id): void;
    public function UpdateActivite(string $id, string $nom,  ?string $description, string $sections_id, string $lieu_id):ActiviteDTO;

    public function getActivite(string $id): ActiviteDTO;
}