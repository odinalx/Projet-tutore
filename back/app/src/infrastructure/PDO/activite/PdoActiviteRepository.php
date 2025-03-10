<?php

namespace slv\infrastructure\PDO\activite;

use slv\core\domain\entities\Activite\Activite;
use slv\core\dto\Activite\ActiviteDTO;
use slv\core\repositoryInterfaces\activite\ActiviteRepositoryInterface;
use PDO;
use PDOException;
use slv\core\dto\activite\UserActivityDTO;

class PdoActiviteRepository implements ActiviteRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function CreateActivite(Activite $activite):void
    {
        try{
            $smt=$this->pdo->prepare('INSERT INTO activite (id, nom, description, sections_id, lieu_id,  date_debut, date_fin, created_at, updated_at ) VALUES (:id, :nom, :description, :sections_id, :lieu_id, :date_debut, :date_fin, :created_at, :updated_at)');
            $smt->execute([
                'id'=>$activite->getID(),
                'nom'=>$activite->nom,
                'description'=>$activite->description,
                'sections_id'=>$activite->sections_id,
                'lieu_id'=>$activite->lieu_id,
                'date_debut'=>$activite->date_debut->format('Y-m-d H:i:s'),
                'date_fin'=>$activite->date_fin->format('Y-m-d H:i:s'),
                'created_at'=>$activite->created_at->format('Y-m-d H:i:s'),
                'updated_at'=>$activite->created_at->format('Y-m-d H:i:s')
            ]);
        }
        catch (PDOException $e)
        {
            throw new PdoActiviteException("Impossible de créer une activité" . $e->getMessage());
        }

    }
    public function DeleteActivite(string $id): void 
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM activite WHERE id = :id');
            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() === 0) {
                throw new PdoActiviteException("Aucune activité trouvée avec l'ID: $id");
            }
        } catch (PDOException $e) {
            throw new PdoActiviteException("Erreur lors de la suppression de l'activité: " . $e->getMessage());
        }
    }

        public function UpdateActivite(string $id, ?string $nom,  ?string $description, ?string $sections_id, ?string $lieu_id):ActiviteDTO
    {

        try {
            $query = "UPDATE activite SET";
            $params = [];

            if ($nom !== null) {
                $query .= " nom = :nom,";
                $params['nom'] = $nom;
            }
            if ($description !== null) {
                $query .= " description = :description,";
                $params['description'] = $description;
            }
            if ($sections_id !== null) {
                $query .= " sections_id = :sections_id,";
                $params['sections_id'] = $sections_id;
            }
            if($lieu_id !== null){
                $query .= " lieu_id = :lieu_id,";
                $params['lieu_id'] = $lieu_id;
            }

            $query .= " updated_at = :updated_at,";
            $params['updated_at'] = (new \DateTime())->format('Y-m-d H:i:s');

            $query = rtrim($query, ',') . " WHERE id = :id";
            $params['id'] = $id;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);

            return $this->getActivite($id);
        } catch (PDOException $e) {
            throw new PdoException("Impossible de mettre à jour l'activité : " . $e->getMessage());
        }
    }

    public function getActivite (string $id ): ActiviteDTO
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM activite WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();

            if ($row === false) {
                throw new PdoActiviteException("Aucune activité trouvée avec l'ID: $id");
            }

            return new ActiviteDTO(
                $row['id'],
                $row['nom'],
                $row['description'],
                $row['sections_id'],
                $row['lieu_id'],
                $row['date_debut'],
                $row['date_fin'],
                $row['created_at'],
                $row['updated_at']
            );
        } catch (PDOException $e) {
            throw new PdoActiviteException("Impossible de récupérer l'activité : " . $e->getMessage());
        }
    }

    public function getActivitesByUser(string $userId): array
    {
        try {
            
            $stmt = $this->pdo->prepare('SELECT *  FROM user_activities  WHERE user_id = :user_id ');
            $stmt->execute(['user_id' => $userId]);

            $rows = $stmt->fetchAll();

            if ($rows === false || empty($rows)) {
                throw new PdoActiviteException("Aucune activité trouvée pour l'utilisateur avec l'ID: $userId");
            }
            
            
            $activites = [];
            foreach ($rows as $row) {
                $activites[] = new UserActivityDTO(
                    $row['user_id'],
                    $row['user_nom'],
                    $row['user_prenom'],
                    $row['activite_id'],
                    $row['activite_nom'],
                    $row['date_debut'],
                    $row['date_fin'],
                    $row['section_id'],
                    $row['section_nom']
                );
            }      
                     #TODO:  vérifier la réponse, elle est encore vide
            
            return $activites;
        } catch (PDOException $e) {
            throw new PdoActiviteException("Impossible de récupérer les activités : " . $e->getMessage());
        }
    }


}