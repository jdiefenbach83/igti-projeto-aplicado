<?php

namespace App\Tests\Unit;

use App\Entity\Consolidation;
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
        $assertType = $this->faker->randomElement(Consolidation::getAssetTypes());
        $negocitionType = $this->faker->randomElement(Consolidation::getNegotiationTypes());
        $totalBought = $this->faker->randomFloat(4, 1, 1_000);
        $totalBoughtCosts = $this->faker->randomFloat(4, 1, 1_000);
        $totalQuantitySold = $this->faker->numberBetween(1, 1000);
        $totalSold = $this->faker->randomFloat(4, 1, 1_000);
        $totalSoldCosts = $this->faker->randomFloat(4, 1, 1_000);
        $accumulatedLoss = $this->faker->randomFloat(4, 1, 1_000);
        $balance = $this->faker->randomFloat(4, 1, 1_000);
        $totalCosts = $this->faker->randomFloat(4, 1, 1_000);
        $compesatedLoss = $this->faker->randomFloat(4, 1, 1_000);
        $irrfCharged = $this->faker->randomFloat(4, 1, 1_000);
        $irrfCalculated = $this->faker->randomFloat(4, 1, 1_000);
        $irToPay = $this->faker->randomFloat(4, 1, 1_000);

        $consolidation = new Consolidation();
        $consolidation
            ->setYear($year)
            ->setMonth($month)
            ->setAssetType($assertType)
            ->setNegotiationType($negocitionType)
            ->setTotalBought($totalBought)
            ->setTotalBoughtCosts($totalBoughtCosts)
            ->setTotalQuantitySold($totalQuantitySold)
            ->setTotalSold($totalSold)
            ->setTotalSoldCosts($totalSoldCosts)
            ->setAccumulatedLoss($accumulatedLoss)
            ->setBalance($balance)
            ->setTotalCosts($totalCosts)
            ->setCompesatedLoss($compesatedLoss)
            ->setIrrfCharged($irrfCharged)
            ->setIrrfCalculated($irrfCalculated)
            ->setIrToPay($irToPay);

        self::assertEquals($year, $consolidation->getYear());
        self::assertEquals($month, $consolidation->getMonth());
        self::assertEquals($assertType, $consolidation->getAssetType());
        self::assertEquals($negocitionType, $consolidation->getNegotiationType());
        self::assertEquals($totalBought, $consolidation->getTotalBought());
        self::assertEquals($totalSold, $consolidation->getTotalSold());
        self::assertEquals($accumulatedLoss, $consolidation->getAccumulatedLoss());
        self::assertEquals($balance, $consolidation->getBalance());
        self::assertEquals($totalCosts, $consolidation->getTotalCosts());
        self::assertEquals($compesatedLoss, $consolidation->getCompesatedLoss());
        self::assertEquals($irrfCharged, $consolidation->getIrrfCharged());
        self::assertEquals($irrfCalculated, $consolidation->getIrrfCalculated());
        self::assertEquals($irToPay, $consolidation->getIrToPay());
    }
}
