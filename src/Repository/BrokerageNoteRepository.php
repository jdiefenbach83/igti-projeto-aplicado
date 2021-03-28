<?php

namespace App\Repository;

use App\Entity\BrokerageNote;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class BrokerageNoteRepository implements BrokerageNoteRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ObjectRepository
     */
    private ObjectRepository $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(BrokerageNote::class);
    }

    public function findAll(): array
    {
        return $this->objectRepository->findBy([], ['date' => 'ASC']);
    }

    public function findById(int $id)
    {
        return $this->objectRepository->find($id);
    }

    public function add(BrokerageNote $brokerage_note): void
    {
        $this->entityManager->persist($brokerage_note);
        $this->entityManager->flush();
    }

    public function update(BrokerageNote $brokerage_note): void
    {
        $this->entityManager->persist($brokerage_note);
        $this->entityManager->flush();
    }

    public function remove(BrokerageNote $brokerage_note): void
    {
        $this->entityManager->remove($brokerage_note);
        $this->entityManager->flush();
    }
}
