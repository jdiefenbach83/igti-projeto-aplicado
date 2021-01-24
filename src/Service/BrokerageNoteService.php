<?php

namespace App\Service;

use App\DataTransferObject\DTOInterface;
use App\Entity\BrokerageNote;
use App\Helper\BrokerageNoteFactory;
use App\Repository\BrokerageNoteRepositoryInterface;
use App\Repository\BrokerRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BrokerageNoteService implements ServiceInterface
{
    /**
     * @var BrokerageNoteRepositoryInterface
     */
    private BrokerageNoteRepositoryInterface $brokerageNoteRepository;
    /**
     * @var BrokerRepositoryInterface
     */
    private BrokerRepositoryInterface $brokerRepository;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    private iterable $validationErrors;

    public function __construct(
        BrokerageNoteRepositoryInterface $brokerageNoteRepository,
        BrokerRepositoryInterface $brokerRepository,
        ValidatorInterface $validator)
    {
        $this->brokerageNoteRepository = $brokerageNoteRepository;
        $this->brokerRepository = $brokerRepository;
        $this->validator = $validator;
    }

    public function getAll(): array {
        return $this->brokerageNoteRepository->findAll();
    }

    public function getById(int $id): ?BrokerageNote
    {
        return $this->brokerageNoteRepository->findById($id);
    }

    public function add(DTOInterface $brokerage_note_dto): ?BrokerageNote
    {
        $errors = $this->validator->validate($brokerage_note_dto);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $brokerage_note_entity = (new BrokerageNoteFactory($this->brokerRepository))->makeEntity($brokerage_note_dto);
        $this->brokerageNoteRepository->add($brokerage_note_entity);

        return $brokerage_note_entity;
    }

    public function update(int $id, DTOInterface $brokerage_note_dto): ?BrokerageNote
    {
        $existing_entity = $this->brokerageNoteRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $errors = $this->validator->validate($brokerage_note_dto);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $brokerage_note_entity = (new BrokerageNoteFactory($this->brokerRepository))->makeEntity($brokerage_note_dto);

        $existing_entity->setBroker($brokerage_note_entity->getBroker());
        $existing_entity->setDate($brokerage_note_entity->getDate());
        $existing_entity->setNumber($brokerage_note_entity->getNumber());
        $existing_entity->setTotalMoviments($brokerage_note_entity->getTotalMoviments());
        $existing_entity->setOperationalFee($brokerage_note_entity->getOperationalFee());
        $existing_entity->setRegistrationFee($brokerage_note_entity->getRegistrationFee());
        $existing_entity->setEmolumentFee($brokerage_note_entity->getEmolumentFee());
        $existing_entity->setIssPisCofins($brokerage_note_entity->getIssPisCofins());
        $existing_entity->setNoteIrrfTax($brokerage_note_entity->getNoteIrrfTax());

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