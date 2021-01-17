<?php

namespace App\Service;

use App\Entity\BrokerageNote;
use App\Entity\EntityInterface;
use App\Repository\BrokerageNoteRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BrokerageNoteService implements ServiceInterface
{
    /**
     * @var BrokerageNoteRepositoryInterface
     */
    private BrokerageNoteRepositoryInterface $brokerageNoteRepository;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    private iterable $validationErrors;

    public function __construct(BrokerageNoteRepositoryInterface $brokerageNoteRepository, ValidatorInterface $validator)
    {
        $this->brokerageNoteRepository = $brokerageNoteRepository;
        $this->validator = $validator;
    }

    public function getAll(): array {
        return $this->brokerageNoteRepository->findAll();
    }

    public function getById(int $id): ?BrokerageNote
    {
        return $this->brokerageNoteRepository->findById($id);
    }

    public function add(EntityInterface $brokerage_note): bool
    {
        $errors = $this->validator->validate($brokerage_note);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return false;
        }

        $this->brokerageNoteRepository->add($brokerage_note);

        return true;
    }

    public function update(int $id, EntityInterface $brokerage_note): ?BrokerageNote
    {
        $existing_entity = $this->brokerageNoteRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $existing_entity->setCode($brokerage_note->getCode());
        $existing_entity->setName($brokerage_note->getName());
        $existing_entity->setCnpj($brokerage_note->getCnpj());
        $existing_entity->setSite($brokerage_note->getSite());

        $errors = $this->validator->validate($existing_entity);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $this->brokerageNoteRepository->add($existing_entity);

        return $existing_entity;
    }

    public function remove(int $id): void
    {
        $existing_entity = $this->brokerageNoteRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $this->brokerageNoteRepository->remove($existing_entity);
    }

    /**
     * @return iterable
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}