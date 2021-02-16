<?php

namespace App\Service;

use App\DataTransferObject\DTOInterface;
use App\Entity\BrokerageNote;
use App\Helper\BrokerageNoteFactory;
use App\Repository\AssetRepositoryInterface;
use App\Repository\BrokerageNoteRepositoryInterface;
use App\Repository\BrokerRepositoryInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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
     * @var AssetRepositoryInterface
     */
    private AssetRepositoryInterface $assetRepository;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;
    /**
     * @var iterable
     */
    private iterable $validationErrors;

    public function __construct(
        BrokerageNoteRepositoryInterface $brokerageNoteRepository,
        BrokerRepositoryInterface $brokerRepository,
        AssetRepositoryInterface $assetRepository,
        ValidatorInterface $validator)
    {
        $this->brokerageNoteRepository = $brokerageNoteRepository;
        $this->brokerRepository = $brokerRepository;
        $this->assetRepository = $assetRepository;
        $this->validator = $validator;
    }

    public function getAll(): array {
        return $this->brokerageNoteRepository->findAll();
    }

    public function getById(int $id): ?BrokerageNote
    {
        return $this->brokerageNoteRepository->findById($id);
    }

    private function validateDTO(DTOInterface $dto): ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($dto);

        foreach($dto->getOperations() as $operation){
            $errors->addAll($this->validator->validate($operation));
        }

        return $errors;
    }

    public function add(DTOInterface $dto): ?BrokerageNote
    {
        $errors = $this->validateDTO($dto);

        if ($errors->count() > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $brokerage_note_factory = new BrokerageNoteFactory($this->brokerRepository, $this->assetRepository);
        $brokerage_note_entity = $brokerage_note_factory->makeEntityFromDTO($dto);

        $this->brokerageNoteRepository->add($brokerage_note_entity);

        return $brokerage_note_entity;
    }

    public function update(int $id, DTOInterface $dto): ?BrokerageNote
    {
        $existing_entity = $this->brokerageNoteRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $errors = $this->validateDTO($dto);

        if ($errors->count() > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $brokerage_note_factory = new BrokerageNoteFactory($this->brokerRepository, $this->assetRepository);
        $brokerage_note_entity = $brokerage_note_factory->makeEntityFromDTO($dto);

        $existing_entity->setBroker($brokerage_note_entity->getBroker());
        $existing_entity->setDate($brokerage_note_entity->getDate());
        $existing_entity->setNumber($brokerage_note_entity->getNumber());
        $existing_entity->setTotalMoviments($brokerage_note_entity->getTotalMoviments());
        $existing_entity->setOperationalFee($brokerage_note_entity->getOperationalFee());
        $existing_entity->setRegistrationFee($brokerage_note_entity->getRegistrationFee());
        $existing_entity->setEmolumentFee($brokerage_note_entity->getEmolumentFee());
        $existing_entity->setIssPisCofins($brokerage_note_entity->getIssPisCofins());
        $existing_entity->setNoteIrrfTax($brokerage_note_entity->getNoteIrrfTax());

        $this->brokerageNoteRepository->update($existing_entity);

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
    public function getValidationErrors(): iterable
    {
        return $this->validationErrors;
    }
}