<?php


namespace App\Service;


use App\Entity\Broker;
use App\Entity\EntityInterface;
use App\Repository\BrokerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BrokerService implements ServiceInterface
{
    /**
     * @var BrokerRepository
     */
    private BrokerRepository $brokerRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    private $validationErrors;

    public function __construct(BrokerRepository $brokerRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->brokerRepository = $brokerRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function getAll(): array {
        return $this->brokerRepository->findAll();
    }

    public function getById(int $id): ?Broker
    {
        return $this->brokerRepository->findOneBy(['id' => $id]);
    }

    public function add(EntityInterface $broker): bool
    {
        $errors = $this->validator->validate($broker);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return false;
        }

        $this->entityManager->persist($broker);
        $this->entityManager->flush();

        return true;
    }

    public function update(int $id, EntityInterface $broker): ?Broker
    {
        $existing_entity = $this->brokerRepository->find($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $existing_entity->setCode($broker->getCode());
        $existing_entity->setName($broker->getName());
        $existing_entity->setCnpj($broker->getCnpj());
        $existing_entity->setSite($broker->getSite());

        $errors = $this->validator->validate($existing_entity);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $this->entityManager->persist($existing_entity);
        $this->entityManager->flush();

        return $existing_entity;
    }

    public function remove(int $id): void
    {
        $existing_entity = $this->brokerRepository->find($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $this->entityManager->remove($existing_entity);
        $this->entityManager->flush();
    }

    /**
     * @return array
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}