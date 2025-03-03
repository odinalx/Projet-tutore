<?php
namespace slv\core\dto\activite;

use slv\core\dto\DTO;

class UserActivityDTO extends DTO
{
    protected string $userId;
    protected string $userNom;
    protected string $userPrenom;
    protected string $activiteId;
    protected string $activiteNom;
    protected string $dateDebut;
    protected string $dateFin;
    protected string $sectionId;
    protected string $sectionNom;

    public function __construct(
        string $userId,
        string $userNom,
        string $userPrenom,
        string $activiteId,
        string $activiteNom,
        string $dateDebut,
        string $dateFin,
        string $sectionId,
        string $sectionNom
    ) {
        $this->userId = $userId;
        $this->userNom = $userNom;
        $this->userPrenom = $userPrenom;
        $this->activiteId = $activiteId;
        $this->activiteNom = $activiteNom;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->sectionId = $sectionId;
        $this->sectionNom = $sectionNom;
    }

    

    public function getUserId(): string {
        return $this->userId;
    }

    public function getUserNom(): string {
        return $this->userNom;
    }

    public function getUserPrenom(): string {
        return $this->userPrenom;
    }

    public function getActiviteId(): string {
        return $this->activiteId;
    }

    public function getActiviteNom(): string {
        return $this->activiteNom;
    }

    public function getDateDebut(): string {
        return $this->dateDebut;
    }

    public function getDateFin(): string {
        return $this->dateFin;
    }

    public function getSectionId(): string {
        return $this->sectionId;
    }

    public function getSectionNom(): string {
        return $this->sectionNom;
    }
}
