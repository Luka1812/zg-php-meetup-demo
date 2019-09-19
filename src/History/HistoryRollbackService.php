<?php

namespace App\Services;

class HistoryRollbackService
{
    public function __construct(EntityManager $entityManager, HistoryRepository $historyRepository)
    {
        $this->entityManager     = $entityManager;
        $this->historyRepository = $historyRepository;
    }

    public function rollback(string $entityName, int $entityId, int $version)
    {
        $entity = $this->entityManager->find($entityName, $entityId);

        $this->historyRepository->revert($entity, $version);

        $this->entityManager->persist($entity);

        $this->entityManager->flush();
    }
}
