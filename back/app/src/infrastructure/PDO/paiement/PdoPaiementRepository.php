<?php

namespace slv\infrastructure\PDO\paiement;

use PDO;
use PDOException;
use slv\core\domain\entities\paiement\Paiement;
use slv\core\domain\entities\paiement\PaiementPartiel;
use slv\core\dto\paiement\PaiementPartielDTO;
use slv\core\dto\paiement\PaiementDTO;
use slv\core\repositoryInterfaces\auth\AuthRepositoryInterface;
use slv\core\repositoryInterfaces\paiement\PaiementRepositoryInterface;
use slv\core\repositoryInterfaces\sections\SectionRepositoryInterface;
use slv\infrastructure\PDO\auth\PdoAuthException;
use slv\infrastructure\PDO\sections\PdoSectionException;

class PdoPaiementRepository implements PaiementRepositoryInterface
{
    private PDO $pdo;
    private AuthRepositoryInterface $authRepository;
    private SectionRepositoryInterface $sectionRepository;

    public function __construct(PDO $pdo, AuthRepositoryInterface $authRepository, SectionRepositoryInterface $sectionRepository)
    {
        $this->pdo = $pdo;
        $this->authRepository = $authRepository;
        $this->sectionRepository = $sectionRepository;
    }

    public function createPaiement(Paiement $paiement): void
    {
        try {
            $this->authRepository->getUserById($paiement->user_id);
            $this->sectionRepository->getSection($paiement->section_id);

            $stmt = $this->pdo->prepare('INSERT INTO paiements (id, montant_total, reste_a_payer, user_id, section_id, updated_at, status) VALUES (:id, :montant_total, :reste_a_payer, :user_id, :section_id, :updated_at, :status)');
            $stmt->execute([
                'id' => $paiement->getID(),
                'montant_total' => $paiement->montant_total,
                'reste_a_payer' => $paiement->reste_a_payer,
                'user_id' => $paiement->user_id,
                'section_id' => $paiement->section_id,
                'updated_at' => $paiement->updated_at->format('Y-m-d H:i:s'),
                'status' => $paiement->status
            ]);
        } catch (PdoAuthException | PdoSectionException $e) {
            throw new PdoPaiementException($e->getMessage());
        } catch (PDOException $e) {
            throw new PdoPaiementException("Impossible de créer un paiement : " . $e->getMessage());
        }
    }

    public function getPaiement(string $id): PaiementDTO
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM paiements WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new PdoPaiementException("Le paiement avec l'ID $id n'existe pas.");
            }

            $paiementsPartiels = $this->getPaiementsPartielsByPaiement($id);

            return new PaiementDTO(
                $row['id'],
                $row['status'],
                $row['montant_total'],
                $row['reste_a_payer'],
                $row['user_id'],
                $row['section_id'],
                $row['updated_at'],
                $paiementsPartiels
            );
        } catch (PDOException $e) {
            throw new PdoPaiementException("Impossible de récupérer le paiement : " . $e->getMessage());
        }
    }

    public function getPaiementsPartielsByPaiement(string $id): array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM paiement_partiel WHERE paiement_id = :paiement_id');
            $stmt->execute(['paiement_id' => $id]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $paiementsPartiels = [];

            foreach ($rows as $row) {
                $paiementsPartiels[] = new PaiementPartielDTO(
                    $row['id'],
                    $row['paiement_id'],
                    $row['montant'],
                    $row['mode_paiement'],
                    $row['date_paiement']
                );
            }

            return $paiementsPartiels;
        } catch (PDOException $e) {
            throw new PdoPaiementException("Impossible de récupérer les paiements partiels : " . $e->getMessage());
        }
    }

    public function deletePaiement(string $id): void
    {
        try {
            $paiement = $this->getPaiement($id);

            if ($paiement) {
                $stmt = $this->pdo->prepare('DELETE FROM paiements WHERE id = :id');
                $stmt->execute(['id' => $id]);
            }
        } catch (PdoPaiementException $e) {
            throw new PdoPaiementException("Impossible de supprimer le paiement : " . $e->getMessage());
        } catch (PDOException $e) {
            throw new PdoPaiementException("Erreur de base de données : " . $e->getMessage());
        }
    }

    public function createPaiementPartiel(PaiementPartiel $paiementPartiel): void
    {
        try {
            $paiement = $this->getPaiement($paiementPartiel->paiement_id);

            if ($paiement->status === 'finalisé') {
                throw new PdoPaiementException("Le paiement est déjà finalisé.");
            }

            if ($paiementPartiel->montant > $paiement->reste_a_payer) {
                throw new PdoPaiementException("Le montant du paiement partiel dépasse le montant restant à payer.");
            }

            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare('INSERT INTO paiement_partiel (paiement_id, montant, mode_paiement, date_paiement, id) VALUES (:paiement_id, :montant, :mode_paiement, :date_paiement, :id)');
            $stmt->execute([
                'id' => $paiementPartiel->getID(),
                'paiement_id' => $paiementPartiel->paiement_id,
                'montant' => $paiementPartiel->montant,
                'mode_paiement' => $paiementPartiel->mode_paiement,
                'date_paiement' => $paiementPartiel->date_paiement->format('Y-m-d H:i:s')
            ]);

            $nouveau_reste_a_payer = $paiement->reste_a_payer - $paiementPartiel->montant;

            $stmt = $this->pdo->prepare('UPDATE paiements SET reste_a_payer = :reste_a_payer WHERE id = :paiement_id');
            $stmt->execute([
                'reste_a_payer' => $nouveau_reste_a_payer,
                'paiement_id' => $paiementPartiel->paiement_id
            ]);

            if ($nouveau_reste_a_payer <= 0) {
                $stmt = $this->pdo->prepare('UPDATE paiements SET status = :status WHERE id = :paiement_id');
                $stmt->execute([
                    'status' => 'finalisé',
                    'paiement_id' => $paiementPartiel->paiement_id
                ]);
            }

            $this->pdo->commit();
        } catch (PdoPaiementException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new PdoPaiementException($e->getMessage());
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new PdoPaiementException("Erreur de base de données : " . $e->getMessage());
        }
    }
}
