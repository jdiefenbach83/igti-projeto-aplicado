<?php

namespace App\Entity;

use DateTimeImmutable;
use JsonSerializable;

class BrokerageNote implements EntityInterface, JsonSerializable
{
    private ?int $id;
    private Broker $broker;
    private DateTimeImmutable $date;
    private int $number;
    private float $total_moviments;
    private float $operational_fee;
    private float $registration_fee;
    private float $emolument_fee;
    private float $iss_pis_cofins;
    private float $total_fees;
    private float $note_irrf_tax;
    private float $calculated_irrf_tax;
    private float $net_total;
    private float $total_costs;
    private float $result;
    private float $calculation_basis_irrf;
    private float $calculated_irrf;

    public function __construct()        
    {
        $this->number = 0;
        $this->total_moviments = .0;
        $this->operational_fee = .0;
        $this->registration_fee = .0;
        $this->emolument_fee = .0;
        $this->iss_pis_cofins = .0;
        $this->total_fees = .0;
        $this->note_irrf_tax = .0;
        $this->calculated_irrf_tax = .0;
        $this->net_total = .0;
        $this->total_costs = .0;
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
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date
     * @return BrokerageNote
     */
    public function setDate(DateTimeImmutable $date): BrokerageNote
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
    public function getTotalMoviments(): float
    {
        return $this->total_moviments;
    }

    /**
     * @param float $total_moviments
     * @return BrokerageNote
     */
    public function setTotalMoviments(float $total_moviments): BrokerageNote
    {
        $this->total_moviments = $total_moviments;
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
        $this->calculateFees();
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
        $this->calculateFees();
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
        $this->calculateFees();
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
    public function getTotalFees(): float
    {
        return $this->total_fees;
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
    public function getTotalCost(): float
    {
        return $this->total_costs;
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

    private function calculateFees(): void
    {
        $this->total_fees = bcadd($this->operational_fee, $this->registration_fee, 4);
        $this->total_fees = bcadd($this->total_fees, $this->emolument_fee, 4);
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'broker_id' => $this->broker->getId(),
            'date' => $this->date->format('Y-m-d'),
            'number' => $this->number,
            'total_moviments' => $this->total_moviments,
            'operational_fee' => $this->operational_fee,
            'registration_fee' => $this->registration_fee,
            'emolument_fee' => $this->emolument_fee,
            'iss_pis_cofins' => $this->iss_pis_cofins,
            'total_fees' => $this->total_fees,
            'note_irrf_tax' => $this->note_irrf_tax,
            'calculated_irrf_tax' => $this->calculated_irrf_tax,
            'net_total' => $this->net_total,
            'total_costs' => $this->total_costs,
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