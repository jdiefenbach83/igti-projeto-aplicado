<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class CompanyRepository extends AbstratctRepository implements CompanyRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager,Company::class);
    }

    public function findAll()
    {
        return $this->objectRepository->findAll();
    }

    public function findById(int $id)
    {
        return $this->objectRepository->find($id);
    }

    public function save(Company $company): void
    {
        $this->entityManager->persist($company);
        $this->processWorkUnit();
    }

    public function remove(Company $company): void
    {
        $this->entityManager->remove($company);
        $this->processWorkUnit();
    }

    public function findByCnpj(string $cnpj)
    {
        return $this->objectRepository->findOneBy(['cnpj' => $cnpj]);
    }
}
