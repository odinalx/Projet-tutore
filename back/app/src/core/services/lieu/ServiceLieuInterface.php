<?php

namespace slv\core\services\lieu;


use slv\core\dto\Lieu\LieuDTO;

interface ServiceLieuInterface {
    public function CreateLieu(?string $nom, ?string $adresse, ?string $ville, ?string $code_postal): void;
 
    public function DeleteLieu(string $id): void;
    public function UpdateLieu(?string $id, ?string $nom, ?string $adresse, ?string $ville, ?string $code_postal): LieuDTO;
   
    public function getLieu(string $id): LieuDTO;
}