<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\Timestampable;
use JsonSerializable;
use Symfony\Component\Validator\Exception\ValidatorException;

class BrokerageNote implements EntityInterface, JsonSerializable
{
    use Timestampable;

    private ?int $id;
    private Broker $broker;
    private DateTimeImmutable $date;
    private int $number;
    private float $total_moviments;
    private float $operational_fee;
    private float $registration_fee;
    private float $emolument_fee;
    private float $brokerage;
    private float $iss_pis_cofins;
    private float $total_fees;
    private float $note_irrf_tax;
    private float $calculated_irrf_tax;
    private float $net_total;
    private float $total_costs;
    private float $result;
    private float $calculation_basis_ir;
    private float $calculated_ir;
    private Collection $operations;
    private float $total_operations;
    private int $version;

    public function __construct()
    {
        $this->number = 0;
        $this->total_moviments = .0;
        $this->operational_fee = .0;
        $this->registration_fee = .0;
        $this->emolument_fee = .0;
        $this->brokerage = .0;
        $this->iss_pis_cofins = .0;
        $this->total_fees = .0;
        $this->note_irrf_tax = .0;
        $this->calculated_irrf_tax = .0;
        $this->net_total = .0;
        $this->total_costs = .0;
        $this->result = .0;
        $this->calculation_basis_ir = .0;
        $this->calculated_ir = .0;
        $this->total_operations = .0;

        $this->operations = new ArrayCollection();
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
    public function getDate(): ?DateTimeImmutable
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
    public function getBrokerage(): float
    {
        return $this->brokerage;
    }

    /**
     * @param float $brokerage
     * @return BrokerageNote
     */
    public function setBrokerage(float $brokerage): BrokerageNote
    {
        $this->brokerage = $brokerage;

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

    /**
     * @return float
     */
    public function getTotalOperations(): float
    {
        return $this->total_operations;
    }

    /**
     * @return Collection
     */
    public function getOperations(): Collection
    {
        return $this->operations;
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
        $this->total_costs = bcadd($this->total_fees, $this->brokerage, 4);
        $this->total_costs = bcadd($this->total_costs, $this->iss_pis_cofins, 4);
        $this->total_costs = bcadd($this->total_costs, $this->note_irrf_tax, 4);
    }

    private function calculateNetTotal(): void
    {
        $this->net_total = bcsub($this->total_moviments, $this->total_costs, 4);
    }

    private function calculateResult(): void
    {
        $this->result = bcsub($this->total_moviments, $this->total_fees, 4);
        $this->result = bcsub($this->result, $this->brokerage, 4);
        $this->result = bcsub($this->result, $this->iss_pis_cofins, 4);
    }

    private function calculateBasisIr(): void
    {
        $this->calculation_basis_ir = .0;

        if ($this->result > .0) {
            $this->calculation_basis_ir = $this->result;
        }
    }

    public function addOperation(string $type, Asset $asset, int $quantity, float $price): Operation
    {
        $line = $this->getLastOperationLine();

        $operation = new Operation(
            $line,
            $type,
            $asset,
            $quantity,
            $price,
            $this
        );

        $this->operations->add($operation);

        $this->total_operations = bcadd($this->total_operations, $operation->getTotalForCalculations(), 2);

        return $operation;
    }

    private function getLastOperationLine(): int
    {
        $max_line = 0;

        foreach ($this->operations as $operation) {
            if ($operation->getLine() >= $max_line) {
                $max_line = $operation->getLine();
            }
        }

        return $max_line + 1;
    }

    public function getOperation(int $line): ?Operation
    {
        $operation = $this->getOperations()->filter(function($item) use ($line){
            return $item->getLine() === $line;
        })->first();

        if (!$operation){
            return null;
        }

        return $operation;
    }

    public function editOperation(int $line, string $type, Asset $asset, int $quantity, float $price): ?Operation
    {
        $operation = $this->getOperation($line);

        if (empty($operation)) {
            return null;
        }

        $old_total = $operation->getTotalForCalculations();

        $operation->setType($type);
        $operation->setAsset($asset);
        $operation->setQuantity($quantity);
        $operation->setPrice($price);

        $this->total_operations = bcsub($this->total_operations, $old_total, 2);
        $this->total_operations = bcadd($this->total_operations, $operation->getTotalForCalculations(), 2);

        return $operation;
    }

    public function removeOperation(int $line): bool
    {
        $operation = $this->getOperation($line);

        if (empty($operation)) {
            return false;
        }

        $this->total_operations = bcsub($this->total_operations, $operation->getTotalForCalculations(), 2);

        return $this->operations->removeElement($operation);
    }

    public function validate(): void
    {
        $total_moviments = abs($this->total_moviments);
        $total_operations = abs($this->total_operations);

        if ($total_operations > $total_moviments) {
            throw new ValidatorException('The total of operations is greater than total of moviments');
        }
    }

    public function hasOperationsCompleted(): bool
    {
        return $this->total_moviments === $this->total_operations;
    }

    public function prorateValues(): void
    {
        if (!$this->hasOperationsCompleted()){
            return;
        }

        $qtdeTotal = .0;

        /** @var Operation $operation */
        foreach ($this->operations as $operation) {
            $qtdeTotal += $operation->getQuantity();
        }

        $totalProratedOperationalFee = .0;
        $totalProratedRegistrationFee = .0;
        $totalProratedEmolumentFee = .0;

        /** @var Operation $operation */
        foreach ($this->operations as $operation) {
            $proportionOperationalFee = bcdiv($this->getOperationalFee(), $qtdeTotal, 2);
            $proratedOperationalFee = bcmul($operation->getQuantity(), $proportionOperationalFee, 2);

            $proportionRegistrationFee = bcdiv($this->getRegistrationFee(), $qtdeTotal, 2);
            $proratedRegistrationFee = bcmul($operation->getQuantity(), $proportionRegistrationFee, 2);

            $proportionEmolumentFee = bcdiv($this->getEmolumentFee(), $qtdeTotal, 2);
            $proratedEmolumentFee = bcmul($operation->getQuantity(), $proportionEmolumentFee, 2);

            $operation->setOperationalFee($proratedOperationalFee);
            $totalProratedOperationalFee = bcadd($totalProratedOperationalFee, $proratedOperationalFee, 2);

            $operation->setRegistrationFee($proratedRegistrationFee);
            $totalProratedRegistrationFee = bcadd($totalProratedRegistrationFee, $proratedRegistrationFee, 2);

            $operation->setEmolumentFee($proratedEmolumentFee);
            $totalProratedEmolumentFee = bcadd($totalProratedEmolumentFee, $proratedEmolumentFee, 2);
        }

        if ($totalProratedOperationalFee <> $this->getOperationalFee()) {
            $diff = bcsub($this->getOperationalFee(), $totalProratedOperationalFee, 2);

            $lastOperation = $this->operations->last();
            $fixedValue = bcadd($lastOperation->getOperationalFee(), $diff, 2);
            $lastOperation->setOperationalFee($fixedValue);
        }

        if ($totalProratedRegistrationFee <> $this->getRegistrationFee()) {
            $diff = bcsub($this->getRegistrationFee(), $totalProratedRegistrationFee, 2);

            $lastOperation = $this->operations->last();
            $fixedValue = bcadd($lastOperation->getRegistrationFee(), $diff, 2);
            $lastOperation->setRegistrationFee($fixedValue);
        }

        if ($totalProratedEmolumentFee <> $this->getEmolumentFee()) {
            $diff = bcsub($this->getEmolumentFee(), $totalProratedEmolumentFee, 2);

            $lastOperation = $this->operations->last();
            $fixedValue = bcadd($lastOperation->getEmolumentFee(), $diff, 2);

            $lastOperation->setEmolumentFee($fixedValue);
        }
    }

    public function jsonSerialize(): array
    {
        $operations = [];

        /** @var Operation $operation */
        foreach ($this->operations as $operation){
            $operations[] = [
                'id' => $operation->getId(),
                'line' => $operation->getLine(),
                'type' => $operation->getType(),
                'asset_id' => $operation->getAsset()->getId(),
                'quantity' => $operation->getQuantity(),
                'price' => $operation->getPrice(),
                'total' => $operation->getTotal(),
            ];
        }

        return [
            'id' => $this->id,
            'broker_id' => $this->broker->getId(),
            'date' => $this->date->format('Y-m-d'),
            'number' => $this->number,
            'total_moviments' => $this->total_moviments,
            'operational_fee' => $this->operational_fee,
            'registration_fee' => $this->registration_fee,
            'emolument_fee' => $this->emolument_fee,
            'brokerage' => $this->brokerage,
            'iss_pis_cofins' => $this->iss_pis_cofins,
            'total_fees' => $this->total_fees,
            'note_irrf_tax' => $this->note_irrf_tax,
            'calculated_irrf_tax' => $this->calculated_irrf_tax,
            'net_total' => $this->net_total,
            'total_costs' => $this->total_costs,
            'result' => $this->result,
            'calculation_basis_ir' => $this->calculation_basis_ir,
            'calculated_ir' => $this->calculated_ir,
            'operations' => $operations,
            'total_operations' => $this->total_operations,
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => 'api/brokerageNotes/' . $this->id
                ],
            ]
        ];
    }
}