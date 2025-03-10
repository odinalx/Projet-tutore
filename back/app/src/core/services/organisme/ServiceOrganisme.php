<?php
namespace slv\core\services\organisme;

use slv\core\domain\entities\organisme\Organisme;
use slv\core\dto\organisme\OrganismeDTO;
use slv\core\repositoryInterfaces\organisme\OrganismeRepostitoryInterface;
use slv\infrastructure\PDO\organisme\PdoOrganismeException;

class ServiceOrganisme implements ServiceOrganismeInterface
{
    private OrganismeRepostitoryInterface $organismeRepository;

    public function __construct(OrganismeRepostitoryInterface $organismeRepository)
    {
        $this->organismeRepository = $organismeRepository;
    }

    public function createOrganisme(string $nom, string $description, string $adresse): void
    {
        try {
            $organisme = new Organisme($nom, $description, $adresse);
            $this->organismeRepository->createOrganisme($organisme);

        } catch (PdoOrganismeException $e) {
            throw new ServiceOrganismeException($e->getMessage());
        }
    }

    public function getOrganisme(string $id): OrganismeDTO
    {
        try {
            return $this->organismeRepository->getOrganisme($id);
        } catch (PdoOrganismeException $e) {
            throw new ServiceOrganismeException($e->getMessage());
        }
    }

    public function deleteOrganisme(string $id): void
    {
        try {
            $this->organismeRepository->deleteOrganisme($id);
        } catch (PdoOrganismeException $e) {
            throw new ServiceOrganismeException($e->getMessage());
        }
    }

    public function updateOrganisme(string $id, ?string $nom, ?string $description, ?string $adresse): OrganismeDTO
    {
        try {
            return $this->organismeRepository->updateOrganisme($id, $nom, $description, $adresse);
        } catch (PdoOrganismeException $e) {
            throw new ServiceOrganismeException($e->getMessage());
        }
    }
}