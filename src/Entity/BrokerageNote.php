<?php

namespace App\Entity;

use DateTime;
use JsonSerializable;

class BrokerageNote implements EntityInterface, JsonSerializable
{
    private ?int $id;
    private Broker $broker;
    private DateTime $date;
    private int $number;
    private float $movimentation_total;
    private float $operational_fee;
    private float $registration_fee;
    private float $emolument_fee;
    private float $iss_pis_cofins;
    private float $fee_total;
    private float $note_irrf_tax;
    private float $calculated_irrf_tax;
    private float $net_total;
    private float $cost_total;
    private float $result;
    private float $calculation_basis_irrf;
    private float $calculated_irrf;

    public function __construct()        
    {
        $this->number = 0;
        $this->movimentation_total = .0;
        $this->operational_fee = .0;
        $this->registration_fee = .0;
        $this->emolument_fee = .0;
        $this->iss_pis_cofins = .0;
        $this->fee_total = .0;
        $this->note_irrf_tax = .0;
        $this->calculated_irrf_tax = .0;
        $this->net_total = .0;
        $this->cost_total = .0;
        $this->result = .0;
        $this->calculation_basis_irrf = .0;
        $this->calculated_irrf = .0;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Broker
     */
    public function getBroker(): Broker
    {
        return $this->broker;
    }

    /**
     * @param Broker $broker
     * @return BrokerageNote
     */
    public function setBroker(Broker $broker): BrokerageNote
    {
        $this->broker = $broker;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return BrokerageNote
     */
    public function setDate(DateTime $date): BrokerageNote
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return BrokerageNote
     */
    public function setNumber(int $number): BrokerageNote
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return float
     */
    public function getMovimentationTotal(): float
    {
        return $this->movimentation_total;
    }

    /**
     * @param float $movimentation_total
     * @return BrokerageNote
     */
    public function setMovimentationTotal(float $movimentation_total): BrokerageNote
    {
        $this->movimentation_total = $movimentation_total;
        return $this;
    }

    /**
     * @return float
     */
    public function getOperationalFee(): float
    {
        return $this->operational_fee;
    }

    /**
     * @param float $operational_fee
     * @return BrokerageNote
     */
    public function setOperationalFee(float $operational_fee): BrokerageNote
    {
        $this->operational_fee = $operational_fee;
        return $this;
    }

    /**
     * @return float
     */
    public function getRegistrationFee(): float
    {
        return $this->registration_fee;
    }

    /**
     * @param float $registration_fee
     * @return BrokerageNote
     */
    public function setRegistrationFee(float $registration_fee): BrokerageNote
    {
        $this->registration_fee = $registration_fee;
        return $this;
    }

    /**
     * @return float
     */
    public function getEmolumentFee(): float
    {
        return $this->emolument_fee;
    }

    /**
     * @param float $emolument_fee
     * @return BrokerageNote
     */
    public function setEmolumentFee(float $emolument_fee): BrokerageNote
    {
        $this->emolument_fee = $emolument_fee;
        return $this;
    }

    /**
     * @return float
     */
    public function getIssPisCofins(): float
    {
        return $this->iss_pis_cofins;
    }

    /**
     * @param float $iss_pis_cofins
     * @return BrokerageNote
     */
    public function setIssPisCofins(float $iss_pis_cofins): BrokerageNote
    {
        $this->iss_pis_cofins = $iss_pis_cofins;
        return $this;
    }

    /**
     * @return float
     */
    public function getFeeTotal(): float
    {
        return $this->fee_total;
    }

    /**
     * @return float
     */
    public function getNoteIrrfTax(): float
    {
        return $this->note_irrf_tax;
    }

    /**
     * @param float $note_irrf_tax
     * @return BrokerageNote
     */
    public function setNoteIrrfTax(float $note_irrf_tax): BrokerageNote
    {
        $this->note_irrf_tax = $note_irrf_tax;
        return $this;
    }

    /**
     * @return float
     */
    public function getCalculatedIrrfTax(): float
    {
        return $this->calculated_irrf_tax;
    }

    /**
     * @return float
     */
    public function getNetTotal(): float
    {
        return $this->net_total;
    }

    /**
     * @return float
     */
    public function getCostTotal(): float
    {
        return $this->cost_total;
    }

    /**
     * @return float
     */
    public function getResult(): float
    {
        return $this->result;
    }

    /**
     * @return float
     */
    public function getCalculationBasisIrrf(): float
    {
        return $this->calculation_basis_irrf;
    }

    /**
     * @return float
     */
    public function getCalculatedIrrf(): float
    {
        return $this->calculated_irrf;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'broker_id' => $this->broker->getId(),
            'date' => $this->date,
            'number' => $this->number,
            'movimentation_total' => $this->movimentation_total,
            'operational_fee' => $this->operational_fee,
            'registration_fee' => $this->registration_fee,
            'emolument_fee' => $this->emolument_fee,
            'iss_pis_cofins' => $this->iss_pis_cofins,
            'fee_total' => $this->fee_total,
            'note_irrf_tax' => $this->note_irrf_tax,
            'calculated_irrf_tax' => $this->calculated_irrf_tax,
            'net_total' => $this->net_total,
            'cost_total' => $this->cost_total,
            'result' => $this->result,
            'calculation_basis_irrf' => $this->calculation_basis_irrf,
            'calculated_irrf' => $this->calculated_irrf,
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => 'api/brokerageNotes/' . $this->id
                ],
            ]
        ];
    }
}