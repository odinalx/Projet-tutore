<?php

namespace slv\core\services\activite;


use slv\core\dto\activite\ActiviteDTO;

interface ServiceActiviteInterface {
    public function CreateActivite(string $nom,  ?string $description, string $sections_id, string $lieu_id, string $date_debut, string $date_fin):void;
    public function DeleteActivite(string $id): void;
    public function UpdateActivite(string $id, string $nom,  ?string $description, string $sections_id, string $lieu_id):ActiviteDTO;

    public function getActivite(string $id): ActiviteDTO;

    public function getActivitesByUser(string $userId): array;
   
    
}