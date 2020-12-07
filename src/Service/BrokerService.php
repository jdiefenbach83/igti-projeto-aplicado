<?php

namespace App\Service;

use App\Entity\Broker;
use App\Entity\EntityInterface;
use App\Repository\BrokerRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BrokerService implements ServiceInterface
{
    /**
     * @var BrokerRepositoryInterface
     */
    private BrokerRepositoryInterface $brokerRepository;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    private iterable $validationErrors;

    public function __construct(BrokerRepositoryInterface $brokerRepository, ValidatorInterface $validator)
    {
        $this->brokerRepository = $brokerRepository;
        $this->validator = $validator;
    }

    public function getAll(): array {
        return $this->brokerRepository->findAll();
    }

    public function getById(int $id): ?Broker
    {
        return $this->brokerRepository->findById($id);
    }

    public function add(EntityInterface $broker): bool
    {
        $errors = $this->validator->validate($broker);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return false;
        }

        $this->brokerRepository->add($broker);

        return true;
    }

    public function update(int $id, EntityInterface $broker): ?Broker
    {
        $existing_entity = $this->brokerRepository->findById($id);

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

        $this->brokerRepository->add($existing_entity);

        return $existing_entity;
    }

    public function remove(int $id): void
    {
        $existing_entity = $this->brokerRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $this->brokerRepository->remove($existing_entity);
    }

    /**
     * @return iterable
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}