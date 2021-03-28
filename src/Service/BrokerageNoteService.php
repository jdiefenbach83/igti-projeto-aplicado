<?php

namespace App\Service;

use App\DataTransferObject\DTOInterface;
use App\DataTransferObject\OperationDTO;
use App\Entity\BrokerageNote;
use App\Entity\Operation;
use App\Helper\BrokerageNoteFactory;
use App\Repository\AssetRepositoryInterface;
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
    /**
     * @var PositionService
     */
    private PositionService $positionService;

    public function __construct(
        BrokerageNoteRepositoryInterface $brokerageNoteRepository,
        BrokerRepositoryInterface $brokerRepository,
        AssetRepositoryInterface $assetRepository,
        ValidatorInterface $validator,
        PositionService $positionService
    )
    {
        $this->brokerageNoteRepository = $brokerageNoteRepository;
        $this->brokerRepository = $brokerRepository;
        $this->assetRepository = $assetRepository;
        $this->validator = $validator;
        $this->positionService = $positionService;
    }

    public function getAll(): array {
        return $this->brokerageNoteRepository->findAll();
    }

    public function getById(int $id): ?BrokerageNote
    {
        return $this->brokerageNoteRepository->findById($id);
    }

    public function add(DTOInterface $dto): ?BrokerageNote
    {
        if (!$this->isDTOValid($dto)) {
            return null;
        }

        $brokerage_note_factory = new BrokerageNoteFactory($this->brokerRepository);
        $brokerage_note_entity = $brokerage_note_factory->makeEntityFromDTO($dto);

        $this->brokerageNoteRepository->add($brokerage_note_entity);

        return $brokerage_note_entity;
    }

    public function update(int $id, DTOInterface $dto): ?BrokerageNote
    {
        /** @var BrokerageNote $existing_entity */
        $existing_entity = $this->brokerageNoteRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        if (!$this->isDTOValid($dto)) {
            return null;
        }

        $brokerage_note_factory = new BrokerageNoteFactory($this->brokerRepository);
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
        $this->positionService->processPosition();

        return $existing_entity;
    }

    public function remove(int $id): void
    {
        $existing_entity = $this->brokerageNoteRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $this->brokerageNoteRepository->remove($existing_entity);
        $this->positionService->processPosition();
    }

    /**
     * @return iterable
     */
    public function getValidationErrors(): iterable
    {
        return $this->validationErrors;
    }

    /**
     * @param int $brokerageNoteId
     * @param OperationDTO $dto
     * @return Operation|null
     */
    public function addOperation(int $brokerageNoteId, OperationDTO $dto): ?Operation
    {
        /** @var BrokerageNote $existingBrokerageNote */
        $existingBrokerageNote = $this->brokerageNoteRepository->findById($brokerageNoteId);

        if (is_null($existingBrokerageNote)) {
            throw new \InvalidArgumentException();
        }

        if (!$this->isDTOValid($dto)) {
            return null;
        }

        $newOperation = $existingBrokerageNote->addOperation(
            $dto->getType(),
            $this->assetRepository->findById($dto->getAssetId()),
            $dto->getQuantity(),
            $dto->getPrice()
        );

        $this->brokerageNoteRepository->update($existingBrokerageNote);
        $this->positionService->processPosition();

        return $newOperation;
    }

    /**
     * @param int $brokerageNoteId
     * @param int $line
     * @param OperationDTO $dto
     * @return Operation|null
     */
    public function updateOperation(int $brokerageNoteId, int $line, OperationDTO $dto): ?Operation
    {
        /** @var BrokerageNote $existingBrokerageNote */
        $existingBrokerageNote = $this->brokerageNoteRepository->findById($brokerageNoteId);

        if (is_null($existingBrokerageNote)) {
            throw new \InvalidArgumentException();
        }

        if (!$this->isDTOValid($dto)) {
            return null;
        }

        $updatedOperation = $existingBrokerageNote->editOperation(
            $line,
            $dto->getType(),
            $this->assetRepository->findById($dto->getAssetId()),
            $dto->getQuantity(),
            $dto->getPrice()
        );

        if (is_null($updatedOperation)) {
            return null;
        }

        $this->brokerageNoteRepository->update($existingBrokerageNote);
        $this->positionService->processPosition();

        return $updatedOperation;
    }

    public function removeOperation(int $brokerageNoteId, int $line): void
    {
        /** @var BrokerageNote $existingBrokerageNote */
        $existingBrokerageNote = $this->brokerageNoteRepository->findById($brokerageNoteId);

        if (is_null($existingBrokerageNote)) {
            throw new \InvalidArgumentException();
        }

        if (!$existingBrokerageNote->getOperation($line)) {
            throw new \InvalidArgumentException();
        }

        $operationRemoved = $existingBrokerageNote->removeOperation($line);

        if (!$operationRemoved) {
            throw new \DomainException('Error to remove operation');
        }

        $this->brokerageNoteRepository->update($existingBrokerageNote);
        $this->positionService->processPosition();
    }

    private function isDTOValid(DTOInterface $dto): bool
    {
        $errors = $this->validator->validate($dto);

        if ($errors->count() > 0) {
            $this->validationErrors = $errors;
            return false;
        }

        return true;
    }
}