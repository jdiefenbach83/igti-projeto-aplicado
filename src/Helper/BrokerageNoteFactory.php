<?php

namespace App\Helper;

use App\DataTransferObject\BrokerageNoteDTO;
use App\Entity\BrokerageNote;
use App\Repository\AssetRepositoryInterface;
use App\Repository\BrokerRepositoryInterface;
use DateTimeImmutable;

class BrokerageNoteFactory
{
    /**
     * @var BrokerRepositoryInterface
     */
    private BrokerRepositoryInterface $brokerRepository;

    public function __construct(BrokerRepositoryInterface $brokerRepository)
    {
        $this->brokerRepository = $brokerRepository;
    }

    /**
     * @param BrokerageNoteDTO $dto
     * @return BrokerageNote
     */
    public function makeEntityFromDTO(BrokerageNoteDTO $dto): BrokerageNote
    {
        return $this->makeEntity(
            $dto->getBrokerId(),
            $dto->getDate(),
            $dto->getNumber(),
            $dto->getTotalMoviments(),
            $dto->getOperationalFee(),
            $dto->getRegistrationFee(),
            $dto->getEmolumentFee(),
            $dto->getIssPisCofins(),
            $dto->getNoteIrrfTax()
        );
    }

    /**
     * @param string $broker_id
     * @param string $date
     * @param string $number
     * @param string $total_moviments
     * @param string $operational_fee
     * @param string $registration_fee
     * @param string $emolument_fee
     * @param string $iss_pis_cofins
     * @param string $note_irrf_tax
     * @return BrokerageNote
     */
    public function makeEntity(
        string $broker_id,
        string $date,
        string $number,
        string $total_moviments,
        string $operational_fee,
        string $registration_fee,
        string $emolument_fee,
        string $iss_pis_cofins,
        string $note_irrf_tax
    ): BrokerageNote
    {
        return (new BrokerageNote())
            ->setBroker($this->brokerRepository->findById($broker_id))
            ->setDate(DateTimeImmutable::createFromFormat('Y-m-d', $date))
            ->setNumber($number)
            ->setTotalMoviments($total_moviments)
            ->setOperationalFee($operational_fee)
            ->setRegistrationFee($registration_fee)
            ->setEmolumentFee($emolument_fee)
            ->setIssPisCofins($iss_pis_cofins)
            ->setNoteIrrfTax($note_irrf_tax);
    }
}