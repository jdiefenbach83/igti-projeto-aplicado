<?php

namespace App\Entity;

use DateTimeImmutable;
use JsonSerializable;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class BrokerageNote implements EntityInterface, JsonSerializable
{
    private ?int $id;
    private ?Broker $broker;
    private ?DateTimeImmutable $date;
    private ?int $number;
    private ?float $total_moviments;
    private ?float $operational_fee;
    private ?float $registration_fee;
    private ?float $emolument_fee;
    private ?float $iss_pis_cofins;
    private ?float $total_fees;
    private ?float $note_irrf_tax;
    private float $calculated_irrf_tax;
    private float $net_total;
    private float $total_costs;
    private float $result;
    private float $calculation_basis_ir;
    private float $calculated_ir;

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
        $this->calculation_basis_ir = .0;
        $this->calculated_ir = .0;
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
    public function getBroker(): ?Broker
    {
        return $this->broker;
    }

    /**
     * @param Broker $broker
     * @return BrokerageNote
     */
    public function setBroker(?Broker $broker): BrokerageNote
    {
        $this->broker = $broker;

        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date
     * @return BrokerageNote
     */

    public function setDate(?DateTimeImmutable $date): BrokerageNote
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
        $this->calculate();

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
        $this->calculate();

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
        $this->calculate();

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
        $this->calculate();

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
        $this->calculate();

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
    public function getTotalCosts(): float
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
    public function getCalculationBasisIr(): float
    {
        return $this->calculation_basis_ir;
    }

    /**
     * @return float
     */
    public function getCalculatedIr(): float
    {
        return $this->calculated_ir;
    }

    private function calculate(): void
    {
        $this->calculateFees();
        $this->calculateTotalCosts();
        $this->calculateNetTotal();
        $this->calculateResult();
        $this->calculateBasisIr();
    }

    private function calculateFees(): void
    {
        $this->total_fees = bcadd($this->operational_fee, $this->registration_fee, 4);
        $this->total_fees = bcadd($this->total_fees, $this->emolument_fee, 4);
    }

    private function calculateTotalCosts(): void
    {
        $this->total_costs = bcadd($this->total_fees, $this->iss_pis_cofins, 4);
        $this->total_costs = bcadd($this->total_costs, $this->note_irrf_tax, 4);
    }

    private function calculateNetTotal(): void
    {
        $this->net_total = bcsub($this->total_moviments, $this->total_costs, 4);
    }

    private function calculateResult(): void
    {
        $this->result = bcsub($this->total_moviments, $this->total_fees, 4);
        $this->result = bcsub($this->result, $this->iss_pis_cofins, 4);
    }

    private function calculateBasisIr(): void
    {
        $this->calculation_basis_ir = .0;

        if ($this->result > .0) {
            $this->calculation_basis_ir = $this->result;
        }
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
            'calculation_basis_ir' => $this->calculation_basis_ir,
            'calculated_ir' => $this->calculated_ir,
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => 'api/brokerageNotes/' . $this->id
                ],
            ]
        ];
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('broker', new NotNull());
        $metadata->addPropertyConstraint('date', new NotBlank());
        $metadata->addPropertyConstraint('date', new Type('DateTimeImmutable'));
        $metadata->addPropertyConstraint('number', new NotBlank());
        $metadata->addPropertyConstraint('number', new Positive());
        //$metadata->addPropertyConstraint('total_moviments', new NotBlank());
        $metadata->addPropertyConstraint('total_moviments', new NotNull());
        $metadata->addPropertyConstraint('total_moviments', new Type('Float'));

        $metadata->addPropertyConstraint('operational_fee', new NotBlank());
        $metadata->addPropertyConstraint('operational_fee', new PositiveOrZero());
        $metadata->addPropertyConstraint('registration_fee', new NotBlank());
        $metadata->addPropertyConstraint('registration_fee', new PositiveOrZero());
        $metadata->addPropertyConstraint('emolument_fee', new NotBlank());
        $metadata->addPropertyConstraint('emolument_fee', new PositiveOrZero());
        $metadata->addPropertyConstraint('iss_pis_cofins', new NotBlank());
        $metadata->addPropertyConstraint('iss_pis_cofins', new PositiveOrZero());
        $metadata->addPropertyConstraint('total_fees', new NotBlank());
        $metadata->addPropertyConstraint('total_fees', new PositiveOrZero());
        $metadata->addPropertyConstraint('note_irrf_tax', new NotBlank());
        $metadata->addPropertyConstraint('note_irrf_tax', new PositiveOrZero());
    }
}