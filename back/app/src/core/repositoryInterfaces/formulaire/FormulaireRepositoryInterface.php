<?php
namespace slv\core\repositoryInterfaces\formulaire;

use slv\core\domain\entities\formulaire\Formulaire;
use slv\core\domain\entities\formulaire\Champ;
use slv\core\dto\formulaire\ChampDTO;
use slv\core\dto\formulaire\FormulaireDTO;

interface FormulaireRepositoryInterface {

    public function createFormulaire(Formulaire $formulaire): void;
    public function deleteFormulaire(string $id): void;
    public function updateFormulaire(string $id, ?string $nom, ?string $description, ?string $section_id): FormulaireDTO;
    public function getFormulaire(string $id): FormulaireDTO;
    public function getChampsByFormulaire(string $formulaireId): array;

    public function createChamp(Champ $champ): void;
    public function getChamp(string $id): ChampDTO;
    public function deleteChamp(string $id): void;

    public function addChampToFormulaire(string $formulaireId, string $champId): void;
}