<?php

namespace slv\core\repositoryInterfaces\lieu;

use slv\core\domain\entities\lieu\Lieu;
use slv\core\dto\lieu\LieuDTO;

interface LieuRepositoryInterface {
    public function createLieu(Lieu $lieu): void;
    public function getLieu(string $id): LieuDTO;
    public function deleteLieu(string $id): void;
    public function updateLieu(string $id, ?string $nom, ?string $adresse, ?string $ville, ?string $code_postal): LieuDTO;
}
