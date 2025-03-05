<?php

namespace slv\core\services\encadrants;

use slv\core\repositoryInterfaces\encadrants\EncadrantRepositoryInterface;

class ServiceEncadrant implements ServiceEncadrantInterface
{
    private EncadrantRepositoryInterface $encadrantRepository;

    public function __construct(EncadrantRepositoryInterface $encadrantRepository)
    {
        $this->encadrantRepository = $encadrantRepository;
    }

    public function getEncadrantsByUserId(string $userId): array
    {
        try {
            return $this->encadrantRepository->getEncadrantsByUserId($userId);
        } catch (\PDOException $e) {
            throw new ServiceEncadrantException($e->getMessage());
        }
    }

    public function removeEncadrantFromSection(string $encadrantId, string $sectionId)
    {
        try {
            $this->encadrantRepository->removeEncadrantFromSection($encadrantId, $sectionId);
        } catch (\PDOException $e) {
            throw new ServiceEncadrantException($e->getMessage());
        }
    }
}
