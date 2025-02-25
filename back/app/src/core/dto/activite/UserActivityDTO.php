<?php
namespace slv\core\dto\activite;

class UserActivityDTO
{
    private string $user_id;
    private string $user_nom;
    private string $user_prenom;
    private string $activite_id;
    private string $activite_nom;
    private \DateTimeImmutable $date_debut;
    private \DateTimeImmutable $date_fin;
    private string $section_id;
    private string $section_nom;

    public function __construct(
        string $user_id,
        string $user_nom,
        string $user_prenom,
        string $activite_id,
        string $activite_nom,
        \DateTimeImmutable $date_debut,
        \DateTimeImmutable $date_fin,
        string $section_id,
        string $section_nom
    ) {
        $this->user_id = $user_id;
        $this->user_nom = $user_nom;
        $this->user_prenom = $user_prenom;
        $this->activite_id = $activite_id;
        $this->activite_nom = $activite_nom;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->section_id = $section_id;
        $this->section_nom = $section_nom;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }

    public function getUserNom(): string
    {
        return $this->user_nom;
    }

    public function getUserPrenom(): string
    {
        return $this->user_prenom;
    }

    public function getActiviteId(): string
    {
        return $this->activite_id;
    }

    public function getActiviteNom(): string
    {
        return $this->activite_nom;
    }

    public function getDateDebut(): \DateTimeImmutable
    {
        return $this->date_debut;
    }

    public function getDateFin(): \DateTimeImmutable
    {
        return $this->date_fin;
    }

    public function getSectionId(): string
    {
        return $this->section_id;
    }

    public function getSectionNom(): string
    {
        return $this->section_nom;
    }
}
