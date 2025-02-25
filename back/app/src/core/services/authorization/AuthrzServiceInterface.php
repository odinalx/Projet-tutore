<?php   
namespace slv\core\services\authorization;
interface AuthrzServiceInterface{
    public function isGrantedOrganisme(string $userid): bool;
    public function isGrantedResponsable(string $userId): bool;
    public function isGrantedSection(string $userid,string $sectionId): bool;
    public function isGrantedFormulaireSection(string $userId, string $formulaireId) : bool;
}