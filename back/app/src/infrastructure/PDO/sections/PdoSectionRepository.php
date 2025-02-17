<?php

namespace slv\infrastructure\PDO\sections;

use PDO;
use PDOException;
use slv\core\domain\entities\sections\Section;
use slv\core\dto\sections\SectionDTO;
use slv\core\repositoryInterfaces\organisme\OrganismeRepostitoryInterface;
use slv\core\repositoryInterfaces\sections\SectionRepositoryInterface;
use slv\infrastructure\PDO\organisme\PdoOrganismeException;

class PdoSectionRepository implements SectionRepositoryInterface
{
    private PDO $pdo;
    private OrganismeRepostitoryInterface $organismeRepository;

    public function __construct(PDO $pdo, OrganismeRepostitoryInterface $organismeRepository)
    {
        $this->pdo = $pdo;
        $this->organismeRepository = $organismeRepository;
    }

    public function createSection(Section $section): void
    {
        try {
            $this->organismeRepository->getOrganisme($section->organisme_id);

            $stmt = $this->pdo->prepare('INSERT INTO sections (id, nom, description, categorie, capacite, tarif, organisme_id, created_at, updated_at) VALUES (:id, :nom, :description, :categorie, :capacite, :tarif, :organisme_id, :created_at, :updated_at)');
            $stmt->execute([
                'id' => $section->getID(),
                'nom' => $section->nom,
                'description' => $section->description,
                'categorie' => $section->categorie,
                'capacite' => $section->capacite,
                'tarif' => $section->tarif,
                'organisme_id' => $section->organisme_id,
                'created_at' => $section->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $section->updated_at->format('Y-m-d H:i:s')
            ]);
        } catch (PdoOrganismeException $e) {
            throw new PdoSectionException($e->getMessage());
        } catch (PDOException $e) {
            throw new PdoSectionException("Impossible de créer une section : " . $e->getMessage());
        }
    }

    public function getSection(string $id): SectionDTO
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM sections WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new PdoSectionException("La section avec l'ID $id n'existe pas.");
            }

            return new SectionDTO(
                $row['id'],
                $row['nom'],
                $row['description'],
                $row['categorie'],
                $row['capacite'],
                $row['tarif'],
                $row['organisme_id'],
                $row['created_at'],
                $row['updated_at']
            );
        } catch (PDOException $e) {
            throw new PdoSectionException("Impossible de récupérer la section : " . $e->getMessage());
        }
    }

    public function deleteSection(string $id): void
    {
        try {
            $section = $this->getSection($id);

            if ($section) {
                $stmt = $this->pdo->prepare('DELETE FROM sections WHERE id = :id');
                $stmt->execute(['id' => $id]);
            }
        } catch (PdoSectionException $e) {
            throw new PdoSectionException("Impossible de supprimer la section : " . $e->getMessage());
        } catch (PDOException $e) {
            throw new PdoSectionException("Erreur de base de données : " . $e->getMessage());
        }
    }

    public function updateSection(string $id, ?string $nom, ?string $description, ?string $categorie, ?int $capacite, ?float $tarif, ?string $organisme_id): SectionDTO
    {
        try {
            if($organisme_id !== null) {
                $this->organismeRepository->getOrganisme($organisme_id);
            }

            $query = "UPDATE sections SET";
            $params = [];

            if ($nom !== null) {
                $query .= " nom = :nom,";
                $params['nom'] = $nom;
            }
            if ($description !== null) {
                $query .= " description = :description,";
                $params['description'] = $description;
            }
            if ($categorie !== null) {
                $query .= " categorie = :categorie,";
                $params['categorie'] = $categorie;
            }
            if ($capacite !== null) {
                $query .= " capacite = :capacite,";
                $params['capacite'] = $capacite;
            }
            if ($tarif !== null) {
                $query .= " tarif = :tarif,";
                $params['tarif'] = $tarif;
            }
            if ($organisme_id !== null) {
                $query .= " organisme_id = :organisme_id,";
                $params['organisme_id'] = $organisme_id;
            }

            $query .= " updated_at = :updated_at,";
            $params['updated_at'] = (new \DateTime())->format('Y-m-d H:i:s');

            $query = rtrim($query, ',') . " WHERE id = :id";
            $params['id'] = $id;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);

            return $this->getSection($id);
        } catch (PdoOrganismeException $e) {
            throw new PdoSectionException($e->getMessage());
        } catch (PDOException $e) {
            throw new PdoSectionException("Impossible de mettre à jour la section : " . $e->getMessage());
        }
    }
}
