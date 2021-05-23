<?php

namespace App\Service\IrCalculator;

use App\Entity\Consolidation;

class NormalMethod implements IrCalculatorMethod
{
    private const IRRF_ALIQUOT = .005;
    private const IR_ALIQUOT = .15;
    private const NET_PROFIT_FREE = 20_000.0;

    private Consolidation $consolidation;

    public function __construct(Consolidation $consolidation)
    {
        $this->consolidation = $consolidation;
    }

    public function getAliquot(): float
    {
        if ($this->shouldCalculate() === false) {
            return .0;
        }

        return self::IR_ALIQUOT;
    }

    public function calculateIrrf(): float
    {
        if ($this->shouldCalculate() === false) {
            return .0;
        }

        return bcmul($this->consolidation->getBasisToIr(), self::IRRF_ALIQUOT, 6);
    }

    public function calculateIr(): float
    {
        if ($this->shouldCalculate() === false) {
            return .0;
        }

        return bcmul($this->consolidation->getBasisToIr(), self::IR_ALIQUOT, 6);
    }

    private function shouldCalculate(): bool
    {
        if (($this->consolidation->getMarketType() !== Consolidation::MARKET_TYPE_SPOT) ||
            ($this->consolidation->getBasisToIr() > self::NET_PROFIT_FREE)) {
            return true;
        }

        return false;
    }
}