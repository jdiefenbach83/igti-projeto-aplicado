<?php

namespace App\Service\IrCalculator;

use App\Entity\Consolidation;

class IrCalculatorFactory
{
    private const NEGOTIATION_TYPE_NORMAL = 'NORMAL';
    private const NEGOTIATION_TYPE_DAYTRADE = 'DAYTRADE';

    public static function getCalculatorMethod(Consolidation $consolidation): IrCalculatorMethod
    {
        switch ($consolidation->getNegotiationType()) {
            case self::NEGOTIATION_TYPE_NORMAL:
                return new NormalMethod($consolidation);
            case self::NEGOTIATION_TYPE_DAYTRADE:
                return new DaytradeMethod($consolidation);
            default:
                throw new \Exception('Invalid negotiation type');
        }
    }
}