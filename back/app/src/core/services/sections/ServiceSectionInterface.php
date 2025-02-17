<?php
namespace slv\core\services\sections;

use slv\core\dto\sections\SectionDTO;

interface ServiceSectionInterface {
    public function createSection(string $nom, string $description, string $categorie, int $capacite, float $tarif, string $organisme_id): void;
    public function getSection(string $id): SectionDTO;
    public function deleteSection(string $id): void;
    public function updateSection(string $id, ?string $nom, ?string $description, ?string $categorie, ?int $capacite, ?float $tarif, ?string $organisme_id ):SectionDTO;
}