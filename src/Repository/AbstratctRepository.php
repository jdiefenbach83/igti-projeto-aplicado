<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

abstract class AbstratctRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @var ObjectRepository
     */
    protected ObjectRepository $objectRepository;

    private bool $isTransaction;

    public function startWorkUnit(): void
    {
        $this->isTransaction = true;
    }

    public function endWorkUnit(): void
    {
        $this->isTransaction = false;
        $this->processWorkUnit();
    }

    protected function processWorkUnit() : void
    {
        if ($this->isTransaction) {
            return;
        }

        $this->entityManager->flush();
    }

    protected function __construct(EntityManagerInterface $entityManager, string $class)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository($class);
        $this->isTransaction = false;
    }
}
