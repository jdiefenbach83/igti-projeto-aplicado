<?php

namespace App\Tests\Unit;

use App\Entity\Consolidation;
use App\Entity\PreConsolidation;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class ConsolidationTest extends TestCase
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }
    
    public function testAsset_ShouldSetAndGetSuccessfully(): void
    {
        $year = $this->faker->numberBetween(1000, 3000);
        $month = $this->faker->numberBetween(1, 12);
        $negocitionType = $this->faker->randomElement(Consolidation::getNegotiationTypes());
        $marketType = $this->faker->randomElement(Consolidation::getMarketTypes());
        $result = $this->faker->randomFloat(4, 1, 1_000);
        $accumulatedLoss = $this->faker->randomFloat(4, 1, 1_000);
        $compesatedLoss = $this->faker->randomFloat(4, 1, 1_000);
        $basisToIr = $this->faker->randomFloat(4, 1, 1_000);
        $aliquot = $this->faker->randomFloat(4, 1, 1_000);
        $irrf = $this->faker->randomFloat(4, 1, 1_000);
        $accumulatedIrrf = $this->faker->randomFloat(4, 1, 1_000);
        $compesatedIrrf = $this->faker->randomFloat(4, 1, 1_000);
        $irrfToPay = $this->faker->randomFloat(4, 1, 1_000);
        $irToPay = $this->faker->randomFloat(4, 1, 1_000);

        $consolidation = new Consolidation();
        $consolidation
            ->setYear($year)
            ->setMonth($month)
            ->setNegotiationType($negocitionType)
            ->setMarketType($marketType)
            ->setResult($result)
            ->setAccumulatedLoss($accumulatedLoss)
            ->setCompesatedLoss($compesatedLoss)
            ->setBasisToIr($basisToIr)
            ->setAliquot($aliquot)
            ->setIrrf($irrf)
            ->setAccumulatedIrrf($accumulatedIrrf)
            ->setCompesatedIrrf($compesatedIrrf)
            ->setIrrfToPay($irrfToPay)
            ->setIrToPay($irToPay);

        self::assertEquals($year, $consolidation->getYear());
        self::assertEquals($month, $consolidation->getMonth());
        self::assertEquals($negocitionType, $consolidation->getNegotiationType());
        self::assertEquals($marketType, $consolidation->getMarketType());
        self::assertEquals($result, $consolidation->getResult());
        self::assertEquals($accumulatedLoss, $consolidation->getAccumulatedLoss());
        self::assertEquals($compesatedLoss, $consolidation->getCompesatedLoss());
        self::assertEquals($basisToIr, $consolidation->getBasisToIr());
        self::assertEquals($aliquot, $consolidation->getAliquot());
        self::assertEquals($irrf, $consolidation->getIrrf());
        self::assertEquals($accumulatedIrrf, $consolidation->getAccumulatedIrrf());
        self::assertEquals($compesatedIrrf, $consolidation->getCompesatedIrrf());
        self::assertEquals($irrfToPay, $consolidation->getIrrfToPay());
        self::assertEquals($irrf, $consolidation->getIrrf());
        self::assertEquals($irToPay, $consolidation->getIrToPay());
    }

    public function testAsset_shouldFailWhenSetAnIncorrectNegotiationType(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid negotiation type');

        $consolidation = new Consolidation();
        $consolidation
            ->setNegotiationType('TEST');
    }

    public function testAsset_shouldFailWhenSetAnIncorrectMarketType(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid market type');

        $consolidation = new Consolidation();
        $consolidation
            ->setMarketType('TEST');
    }
}
