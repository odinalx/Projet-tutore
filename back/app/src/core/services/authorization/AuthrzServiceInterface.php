<?php   
namespace slv\core\services\authorization;
interface AuthrzServiceInterface{
    public function isGrantedOrganisme(string $userid): bool;
    public function isGrantedCreateSection(string $userId): bool;
    public function isGrantedSection(string $userid,string $sectionId): bool;
}