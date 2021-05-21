<?php

namespace App\Service;

use App\DataTransferObject\DTOInterface;
use App\Entity\Broker;
use App\Helper\BrokerFactory;
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

    public function add(DTOInterface $dto): ?Broker
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $broker_entity = (new BrokerFactory())->makeEntityFromDTO($dto);
        $this->brokerRepository->save($broker_entity);

        return $broker_entity;
    }

    public function update(int $id, DTOInterface $dto): ?Broker
    {
        $existing_entity = $this->brokerRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $broker_entity = (new BrokerFactory())->makeEntityFromDTO($dto);

        $existing_entity->setCode($broker_entity->getCode());
        $existing_entity->setName($broker_entity->getName());
        $existing_entity->setCnpj($broker_entity->getCnpj());
        $existing_entity->setSite($broker_entity->getSite());

        $this->brokerRepository->save($existing_entity);

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
    public function getValidationErrors(): iterable
    {
        return $this->validationErrors;
    }
}