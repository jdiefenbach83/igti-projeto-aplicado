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
    /**
     * @var AssetRepositoryInterface
     */
    private AssetRepositoryInterface $assetRepository;

    public function __construct(
        BrokerRepositoryInterface $brokerRepository,
        AssetRepositoryInterface $assetRepository
    )
    {
        $this->brokerRepository = $brokerRepository;
        $this->assetRepository = $assetRepository;
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
            $dto->getNoteIrrfTax(),
            $dto->getOperations()
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
     * @param array $operations
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
        string $note_irrf_tax,
        array $operations
    ): BrokerageNote
    {
        $brokerageNote = (new BrokerageNote())
            ->setBroker($this->brokerRepository->findById($broker_id))
            ->setDate(DateTimeImmutable::createFromFormat('Y-m-d', $date))
            ->setNumber($number)
            ->setTotalMoviments($total_moviments)
            ->setOperationalFee($operational_fee)
            ->setRegistrationFee($registration_fee)
            ->setEmolumentFee($emolument_fee)
            ->setIssPisCofins($iss_pis_cofins)
            ->setNoteIrrfTax($note_irrf_tax);

        foreach ($operations as $operation) {
            $brokerageNote->addOperation(
                $operation->getType(),
                $this->assetRepository->findById($operation->getAssetId()),
                $operation->getQuantity(),
                $operation->getPrice()
            );
        }

        return $brokerageNote;
    }
}