<?php

namespace slv\core\repositoryInterfaces\sections;

use slv\core\domain\entities\sections\Section;
use slv\core\dto\sections\SectionDTO;

interface SectionRepositoryInterface {
    public function createSection(Section $section): void;
    public function getSection(string $id): SectionDTO;
    public function deleteSection(string $id): void;
    public function updateSection(string $id, ?string $nom, ?string $description, ?string $categorie, ?int $capacite, ?float $tarif, ?string $organisme_id ):SectionDTO;
    public function getSectionsByUser(string $user_id): array;
}