<?php

namespace App\Helper;

use App\Entity\BrokerageNote;
use App\Repository\BrokerRepositoryInterface;

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

    public function makeEntity(string $json)
    {
        $content = json_decode($json);

        return (new BrokerageNote())
            ->setBroker($content->broker_id ? $this->broker_repository->findById($content->broker_id) : null)
            ->setDate($content->date ? \DateTimeImmutable::createFromFormat('Y-m-d', $content->date) : null)
            ->setNumber($content->number ?? 0)
            ->setTotalMoviments($content->total_moviments ?? .0)
            ->setOperationalFee($content->operational_fee ?? .0)
            ->setRegistrationFee($content->registration_fee ?? .0)
            ->setEmolumentFee($content->emolument_fee ?? .0)
            ->setIssPisCofins($content->iss_pis_cofins ?? .0)
            ->setNoteIrrfTax($content->note_irrf_tax ?? .0);
    }
}