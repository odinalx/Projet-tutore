<?php
namespace slv\core\services\organisme;

use slv\core\dto\organisme\OrganismeDTO;

interface ServiceOrganismeInterface {
    public function createOrganisme(string $nom, string $description, string $adresse): void;
    public function getOrganisme(string $id): OrganismeDTO;
    public function deleteOrganisme(string $id): void;
    public function updateOrganisme(string $id, ?string $nom, ?string $description, ?string $adresse): OrganismeDTO;
}