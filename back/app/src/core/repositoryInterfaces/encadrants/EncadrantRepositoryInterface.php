<?php
namespace slv\core\repositoryInterfaces\encadrants;

interface EncadrantRepositoryInterface {
    public function getEncadrantsByUserId(string $userId): array;
    public function removeEncadrantFromSection(string $encadrantId, string $sectionId);
}