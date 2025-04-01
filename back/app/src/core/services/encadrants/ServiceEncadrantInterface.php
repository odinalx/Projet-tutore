<?php

namespace slv\core\services\encadrants;

interface ServiceEncadrantInterface {
    public function getEncadrantsByUserId(string $userId): array;
    public function removeEncadrantFromSection(string $encadrantId, string $sectionId);
}