<?php
namespace slv\core\services\formulaire;

use slv\core\dto\formulaire\FormulaireDTO;
use slv\core\dto\formulaire\ChampDTO;

interface ServiceFormulaireInterface {
    public function createFormulaire(string $nom, string $description, string $section_id): void;
    public function getFormulaire(string $id): FormulaireDTO;
    public function deleteFormulaire(string $id): void;
    public function updateFormulaire(string $id, ?string $nom, ?string $description, ?string $section_id): FormulaireDTO;

    public function createChamp(string $nom, string $description): void;
    public function getChamp(string $id): ChampDTO;
    public function deleteChamp(string $id): void;

    public function addChampToFormulaire(string $formulaireId, string $champId): void;
    
}