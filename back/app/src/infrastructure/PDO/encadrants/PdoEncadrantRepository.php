<?php

namespace slv\infrastructure\PDO\encadrants;

use PDO;
use slv\core\dto\encadrants\EncadrantDTO;
use slv\core\repositoryInterfaces\encadrants\EncadrantRepositoryInterface;

class PdoEncadrantRepository implements EncadrantRepositoryInterface
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getEncadrantsByUserId(string $userId): array
    {
        try {
            $sql = "SELECT 
                    u.id AS encadrant_id,
                    u.nom AS encadrant_nom,
                    u.prenom AS encadrant_prenom,
                    u.mail AS encadrant_email,
                    us_encadrant.role AS encadrant_role,
                    s.id AS section_id,
                    s.nom AS section_nom
                FROM user_section us_encadrant
                JOIN users u ON us_encadrant.user_id = u.id
                JOIN sections s ON us_encadrant.section_id = s.id
                JOIN user_section us_responsable ON s.id = us_responsable.section_id
                WHERE us_encadrant.role = 5
                AND us_responsable.user_id = :responsable_id
                AND us_responsable.role = 10";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['responsable_id' => $userId]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Transformer les résultats en objets EncadrantDTO
            $encadrants = [];
            foreach ($results as $row) {
                $encadrants[] = new EncadrantDTO(
                    $row['encadrant_id'],
                    $row['encadrant_nom'],
                    $row['encadrant_prenom'],
                    $row['encadrant_email'],
                    $row['encadrant_role'],
                    $row['section_id'],
                );
            }

            return $encadrants;
        } catch (\PDOException $e) {
            throw new PdoEncadrantException("Erreur lors de la récupération des encadrants : " . $e->getMessage());
        }
    }

    public function removeEncadrantFromSection(string $encadrantId, string $sectionId): bool
    {
        try {
            $sql = "DELETE FROM user_section 
                    WHERE user_id = :encadrant_id AND section_id = :section_id AND role = 5";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'encadrant_id' => $encadrantId,
                'section_id' => $sectionId
            ]);

            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            throw new PdoEncadrantException("Erreur lors de la suppression de l'encadrant de la section : " . $e->getMessage());
        }
    }
}
