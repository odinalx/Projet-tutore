<?php

namespace slv\infrastructure\PDO\organisme;

use slv\core\domain\entities\organisme\Organisme;
use slv\core\dto\organisme\OrganismeDTO;
use slv\core\repositoryInterfaces\organisme\OrganismeRepostitoryInterface;
use PDO;
use PDOException;

class PdoOrganismeRepository implements OrganismeRepostitoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createOrganisme(Organisme $organisme): void
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO organismes (id, nom, description, adresse, created_at, updated_at) VALUES (:id, :nom, :description, :adresse, :created_at, :updated_at)');
            $stmt->execute([
                'id' => $organisme->getID(),
                'nom' => $organisme->nom,
                'description' => $organisme->description,
                'adresse' => $organisme->adresse,
                'created_at' => $organisme->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $organisme->updated_at->format('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            throw new PdoOrganismeException("Impossible de créer un organisme" . $e->getMessage());
        }
    }

    public function getOrganisme(string $id): OrganismeDTO
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM organismes WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new PdoOrganismeException("L'organisme avec l'ID $id n'existe pas.");
            }

            return new OrganismeDTO(
                $row['id'],
                $row['nom'],
                $row['description'],
                $row['adresse'],
                $row['created_at'],
                $row['updated_at']
            );
        } catch (PDOException $e) {
            throw new PdoOrganismeException("Impossible de récupérer l'organisme : " . $e->getMessage());
        }
    }


    public function deleteOrganisme(string $id): void
    {
        try {
            $organisme = $this->getOrganisme($id);

            if ($organisme) {
                $stmt = $this->pdo->prepare('DELETE FROM organismes WHERE id = :id');
                $stmt->execute(['id' => $id]);
            }
        } catch (PdoOrganismeException $e) {
            throw new PdoOrganismeException("Impossible de supprimer l'organisme : " . $e->getMessage());
        } catch (PDOException $e) {
            throw new PdoOrganismeException("Erreur de base de données : " . $e->getMessage());
        }
    }


    public function updateOrganisme(string $id, ?string $nom, ?string $description, ?string $adresse): OrganismeDTO
    {
        try {
            $query = "UPDATE organismes SET";
            $params = [];

            if ($nom !== null) {
                $query .= " nom = :nom,";
                $params['nom'] = $nom;
            }
            if ($description !== null) {
                $query .= " description = :description,";
                $params['description'] = $description;
            }
            if ($adresse !== null) {
                $query .= " adresse = :adresse,";
                $params['adresse'] = $adresse;
            }

            $query .= " updated_at = :updated_at,";
            $params['updated_at'] = (new \DateTime())->format('Y-m-d H:i:s');

            $query = rtrim($query, ',') . " WHERE id = :id";
            $params['id'] = $id;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);

            return $this->getOrganisme($id);
        } catch (PDOException $e) {
            throw new PdoOrganismeException("Impossible de mettre à jour l'organisme : " . $e->getMessage());
        }
    }
}
