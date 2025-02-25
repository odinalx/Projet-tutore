<?php

namespace slv\core\services\lieu;


use slv\core\dto\Lieu\LieuDTO;

interface ServiceLieuInterface {
    public function CreateLieu(string $nom,  ?string $description, string $sections_id, string $lieu_id, string $date_debut, string $date_fin):void;
    public function DeleteLieu(string $id): void;
    public function UpdateLieu(string $id, string $nom,  ?string $description, string $sections_id, string $lieu_id):LieuDTO;

    public function getLieu(string $id): LieuDTO;
}