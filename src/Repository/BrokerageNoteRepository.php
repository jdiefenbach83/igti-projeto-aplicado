<?php

namespace App\Repository;

use App\Entity\BrokerageNote;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

class BrokerageNoteRepository extends AbstratctRepository implements BrokerageNoteRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, BrokerageNote::class);
    }

    public function findAll(): array
    {
        return $this->objectRepository->findBy([], ['date' => 'ASC']);
    }

    public function findById(int $id)
    {
        return $this->objectRepository->find($id);
    }

    public function save(BrokerageNote $brokerage_note): void
    {
        $this->entityManager->persist($brokerage_note);
        $this->processWorkUnit();
    }

    public function remove(BrokerageNote $brokerage_note): void
    {
        $this->entityManager->remove($brokerage_note);
        $this->processWorkUnit();
    }
}
