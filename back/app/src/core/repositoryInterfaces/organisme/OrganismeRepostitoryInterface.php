<?php
namespace slv\core\repositoryInterfaces\organisme;

use slv\core\domain\entities\organisme\Organisme;
use slv\core\dto\organisme\OrganismeDTO;

interface OrganismeRepostitoryInterface {
    public function createOrganisme(Organisme $organisme): void;
    public function getOrganisme(string $id): OrganismeDTO;
    public function deleteOrganisme(string $id): void;
    public function updateOrganisme(string $id, ?string $nom, ?string $description, ?string $adresse):OrganismeDTO;
    public function getOrganismes(): array;
}