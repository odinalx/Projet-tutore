<?php

namespace slv\infrastructure\PDO\formulaire;

use slv\core\domain\entities\formulaire\Formulaire;
use slv\core\dto\formulaire\FormulaireDTO;
use slv\infrastructure\PDO\formulaire\PdoFormulaireException;
use slv\core\dto\formulaire\ChampDTO;
use slv\core\repositoryInterfaces\formulaire\FormulaireRepositoryInterface;
use PDO;
use PDOException;
use slv\core\domain\entities\formulaire\Champ;

class PdoFormulaireRepository implements FormulaireRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createFormulaire(Formulaire $formulaire): void
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO formulaire (id, nom, description, section_id, created_at, updated_at) VALUES (:id, :nom, :description, :section_id, :created_at, :updated_at)');
            $stmt->execute([
                'id' => $formulaire->getID(),
                'nom' => $formulaire->nom,
                'description' => $formulaire->description,
                'section_id' => $formulaire->section_id,
                'created_at' => $formulaire->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $formulaire->updated_at->format('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            throw new PdoFormulaireException("Impossible de créer un formulaire" . $e->getMessage());
        }
    }

    public function getFormulaire(string $id): FormulaireDTO
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM formulaire WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new PdoFormulaireException("Le formulaire avec l'ID $id n'existe pas.");
            }

            $champs = $this->getChampsByFormulaire($id);

            return new FormulaireDTO(
                $row['id'],
                $row['nom'],
                $row['description'],
                $row['section_id'],
                $row['created_at'],
                $row['updated_at'],
                $champs
            );
        } catch (PDOException $e) {
            throw new PdoFormulaireException("Impossible de récupérer le formulaire : " . $e->getMessage());
        }
    }

    public function getChampsByFormulaire(string $formulaireId): array
    {
        try {
            
            // Récupération des champs associés au formulaire
            $stmt = $this->pdo->prepare('
            SELECT c.* FROM champ c
            INNER JOIN champ_formulaire cf ON c.id = cf.champ_id
            WHERE cf.formulaire_id = :formulaire_id
        ');
            $stmt->execute(['formulaire_id' => $formulaireId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$rows) {
                return [];
            }

            // Transformation des résultats en objets ChampDTO
            $champs = [];
            foreach ($rows as $row) {
                $champs[] = new ChampDTO(
                    $row['id'],
                    $row['nom'],
                    $row['description'],
                    $row['created_at'],
                    $row['updated_at']
                );
            }

            return $champs;
        } catch (PdoFormulaireException $e) {
            throw new PdoFormulaireException($e->getMessage());
        } catch (PDOException $e) {
            throw new PdoFormulaireException("Impossible de récupérer les champs du formulaire : " . $e->getMessage());
        }
    }


    public function deleteFormulaire(string $id): void
    {
        try {
            $formulaire = $this->getFormulaire($id);

            if ($formulaire) {
                $stmt = $this->pdo->prepare('DELETE FROM formulaire WHERE id = :id');
                $stmt->execute(['id' => $id]);
            }
        } catch (PdoFormulaireException $e) {
            throw new PdoFormulaireException("Impossible de supprimer le formulaire : " . $e->getMessage());
        } catch (PDOException $e) {
            throw new PdoFormulaireException("Erreur de base de données : " . $e->getMessage());
        }
    }

    public function updateFormulaire(string $id, ?string $nom, ?string $description, ?string $section_id): FormulaireDTO
    {
        try {
            $query = "UPDATE formulaire SET";
            $params = [];

            if ($nom !== null) {
                $query .= " nom = :nom,";
                $params['nom'] = $nom;
            }
            if ($description !== null) {
                $query .= " description = :description,";
                $params['description'] = $description;
            }
            if ($section_id !== null) {
                $query .= " section_id = :section_id,";
                $params['section_id'] = $section_id;
            }

            $query .= " updated_at = :updated_at,";
            $params['updated_at'] = (new \DateTime())->format('Y-m-d H:i:s');

            $query = rtrim($query, ',') . " WHERE id = :id";
            $params['id'] = $id;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);

            return $this->getFormulaire($id);
        } catch (PDOException $e) {
            throw new PdoFormulaireException("Impossible de mettre à jour le formulaire : " . $e->getMessage());
        }
    }

    public function createChamp(Champ $champ): void
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO champ (id, nom, description, created_at, updated_at) VALUES (:id, :nom, :description, :created_at, :updated_at)');
            $stmt->execute([
                'id' => $champ->getID(),
                'nom' => $champ->nom,
                'description' => $champ->description,
                'created_at' => $champ->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $champ->updated_at->format('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            throw new PdoFormulaireException("Impossible de créer un champ" . $e->getMessage());
        }
    }

    public function getChamp(string $id): ChampDTO
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM champ WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new PdoFormulaireException("Le champ avec l'ID $id n'existe pas.");
            }

            return new ChampDTO(
                $row['id'],
                $row['nom'],
                $row['description'],
                $row['created_at'],
                $row['updated_at']
            );
        } catch (PDOException $e) {
            throw new PdoFormulaireException("Impossible de récupérer le champ : " . $e->getMessage());
        }
    }

    public function deleteChamp(string $id): void
    {
        try {
            $champ = $this->getChamp($id);

            if ($champ) {
                $stmt = $this->pdo->prepare('DELETE FROM champ WHERE id = :id');
                $stmt->execute(['id' => $id]);
            }
        } catch (PdoFormulaireException $e) {
            throw new PdoFormulaireException("Impossible de supprimer le champ : " . $e->getMessage());
        } catch (PDOException $e) {
            throw new PdoFormulaireException("Erreur de base de données : " . $e->getMessage());
        }
    }

    public function addChampToFormulaire(string $formulaireId, string $champId): void
    {
        try {
            $this->getFormulaire($formulaireId);
            $this->getChamp($champId);

            $stmt = $this->pdo->prepare('INSERT INTO champ_formulaire (champ_id, formulaire_id) VALUES (:champ_id, :formulaire_id)');
            $stmt->execute([
                'formulaire_id' => $formulaireId,
                'champ_id' => $champId
            ]);
        } catch (PdoFormulaireException $e) {
            throw new PdoFormulaireException($e->getMessage());
        } catch (PDOException $e) {
            throw new PdoFormulaireException("Impossible d'ajouter le champ au formulaire : " . $e->getMessage());
        }
    }
}
