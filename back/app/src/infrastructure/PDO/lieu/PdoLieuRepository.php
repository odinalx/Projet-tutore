<?php

namespace slv\infrastructure\PDO\lieu;

use slv\core\domain\entities\lieu\Lieu;
use slv\core\dto\lieu\LieuDTO;
use slv\core\repositoryInterfaces\lieu\LieuRepositoryInterface;
use PDO;
use PDOException;

class PdoLieuRepository implements LieuRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createLieu(Lieu $lieu): void
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO lieux (id, nom, adresse, ville, code_postal, created_at, updated_at) VALUES (:id, :nom, :adresse, :ville, :code_postal, :created_at, :updated_at)');
            $stmt->execute([
                'id' => $lieu->getID(),
                'nom' => $lieu->nom,
                'adresse' => $lieu->adresse,
                'ville' => $lieu->ville,
                'code_postal' => $lieu->code_postal,
                'created_at' => $lieu->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $lieu->updated_at->format('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            throw new PdoLieuException("Impossible de créer le lieu : " . $e->getMessage());
        }
    }

    public function getLieu(string $id): LieuDTO
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM lieux WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new PdoLieuException("Le lieu avec l'ID $id n'existe pas.");
            }

            return new LieuDTO(
                $row['id'],
                $row['nom'],
                $row['adresse'],
                $row['ville'],
                $row['code_postal'],
                $row['created_at'],
                $row['updated_at']
            );
        } catch (PDOException $e) {
            throw new PdoLieuException("Impossible de récupérer le lieu : " . $e->getMessage());
        }
    }

    public function deleteLieu(string $id): void
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM lieux WHERE id = :id');
            $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            throw new PdoLieuException("Impossible de supprimer le lieu : " . $e->getMessage());
        }
    }

    public function updateLieu(string $id, ?string $nom, ?string $adresse, ?string $ville, ?string $code_postal): LieuDTO
    {
        try {
            $query = "UPDATE lieux SET";
            $params = [];

            if ($nom !== null) {
                $query .= " nom = :nom,";
                $params['nom'] = $nom;
            }
            if ($adresse !== null) {
                $query .= " adresse = :adresse,";
                $params['adresse'] = $adresse;
            }
            if ($ville !== null) {
                $query .= " ville = :ville,";
                $params['ville'] = $ville;
            }
            if ($code_postal !== null) {
                $query .= " code_postal = :code_postal,";
                $params['code_postal'] = $code_postal;
            }

            $query .= " updated_at = :updated_at";
            $params['updated_at'] = (new \DateTime())->format('Y-m-d H:i:s');
            $query .= " WHERE id = :id";
            $params['id'] = $id;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);

            return $this->getLieu($id);
        } catch (PDOException $e) {
            throw new PdoLieuException("Impossible de mettre à jour le lieu : " . $e->getMessage());
        }
    }
}
