<?php

namespace App\Helper;

use App\DataTransferObject\BrokerageNoteDTO;
use App\Entity\BrokerageNote;
use App\Repository\BrokerRepositoryInterface;
use DateTimeImmutable;

class BrokerageNoteFactory implements EntityFactoryInterface
{
    /**
     * @var BrokerRepositoryInterface
     */
    private BrokerRepositoryInterface $broker_repository;

    public function __construct(BrokerRepositoryInterface $broker_repository)
    {
        $this->broker_repository = $broker_repository;
    }

    /**
     * @param BrokerageNoteDTO $dto
     * @return BrokerageNote
     */
    public function makeEntity($dto)
    {
        return (new BrokerageNote())
            ->setBroker($this->broker_repository->findById($dto->getBrokerId()))
            ->setDate(DateTimeImmutable::createFromFormat('Y-m-d', $dto->getDate()))
            ->setNumber($dto->getNumber())
            ->setTotalMoviments($dto->getTotalMoviments())
            ->setOperationalFee($dto->getOperationalFee())
            ->setRegistrationFee($dto->getRegistrationFee())
            ->setEmolumentFee($dto->getEmolumentFee())
            ->setIssPisCofins($dto->getIssPisCofins())
            ->setNoteIrrfTax($dto->getNoteIrrfTax());
    }
}