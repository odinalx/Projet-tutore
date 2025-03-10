<?php

namespace slv\core\services\formulaire;

use slv\core\repositoryInterfaces\formulaire\FormulaireRepositoryInterface;
use slv\core\domain\entities\formulaire\Formulaire;
use slv\core\dto\formulaire\FormulaireDTO;
use slv\core\domain\entities\formulaire\Champ;
use slv\core\dto\formulaire\ChampDTO;
use slv\infrastructure\PDO\formulaire\PdoFormulaireException;

class ServiceFormulaire implements ServiceFormulaireInterface
{
    private FormulaireRepositoryInterface $formulaireRepository;

    public function __construct(FormulaireRepositoryInterface $formulaireRepository)
    {
        $this->formulaireRepository = $formulaireRepository;
    }

    public function createFormulaire(string $nom, string $description, string $section_id): void
    {   
        try {
            $section = new Formulaire($nom, $description, $section_id);
            $this->formulaireRepository->createFormulaire($section);
        } catch (PdoFormulaireException $e) {
            throw new ServiceFormulaireException($e->getMessage());
        }
    }

    public function getFormulaire(string $id): FormulaireDTO
    {
        try {
            return $this->formulaireRepository->getFormulaire($id);
        } catch (PdoFormulaireException $e) {
            throw new ServiceFormulaireException($e->getMessage());
        }
    }

    public function deleteFormulaire(string $id): void
    {
        try {
            $this->formulaireRepository->deleteFormulaire($id);
        } catch (PdoFormulaireException $e) {
            throw new ServiceFormulaireException($e->getMessage());
        }
    }

    public function updateFormulaire(string $id, ?string $nom, ?string $description, ?string $section_id): FormulaireDTO
    {
        try {
            return $this->formulaireRepository->updateFormulaire($id, $nom, $description, $section_id);
        } catch (PdoFormulaireException $e) {
            throw new ServiceFormulaireException($e->getMessage());
        }
    }

    public function createChamp(string $nom, string $description): void
    {
        try {
            $champ = new Champ($nom, $description);
            $this->formulaireRepository->createChamp($champ);
        } catch (PdoFormulaireException $e) {
            throw new ServiceFormulaireException($e->getMessage());
        }
    }

    public function getChamp(string $id): ChampDTO
    {
        try {
            return $this->formulaireRepository->getChamp($id);
        } catch (PdoFormulaireException $e) {
            throw new ServiceFormulaireException($e->getMessage());
        }
    }

    public function deleteChamp(string $id): void
    {
        try {
            $this->formulaireRepository->deleteChamp($id);
        } catch (PdoFormulaireException $e) {
            throw new ServiceFormulaireException($e->getMessage());
        }
    }

    public function addChampToFormulaire(string $formulaireId, string $champId): void
    {
        try {
            $this->formulaireRepository->addChampToFormulaire($formulaireId, $champId);
        } catch (PdoFormulaireException $e) {
            throw new ServiceFormulaireException($e->getMessage());
        }
    }

    
}
    