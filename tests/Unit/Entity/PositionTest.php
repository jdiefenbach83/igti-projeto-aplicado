<?php

namespace App\Tests\Unit;

use App\Entity\Asset;
use App\Entity\Company;
use App\Entity\Position;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    private function buildAsset(): Asset
    {
        $cnpj = $this->faker->text(18);
        $name = $this->faker->text(255);

        $company = new Company();
        $company
            ->setCnpj($cnpj)
            ->setName($name);

        $types = [
            Asset::TYPE_STOCK,
            Asset::TYPE_FUTURE_CONTRACT
        ];

        $code = $this->faker->text(10);
        $type = $this->faker->randomElement($types);

        $asset = new Asset();
        $asset
            ->setCode($code)
            ->setType($type)
            ->setCompany($company);

        return $asset;
    }

    public function testAsset_ShouldSetAndGetSuccessfully(): void
    {
       $asset = $this->buildAsset();
       $sequence = $this->faker->numberBetween(1, 100);
       $type = $this->faker->randomElement(Position::getTypes());
       $date = \DateTimeImmutable::createFromMutable($this->faker->dateTime());
       $quantity = $this->faker->numberBetween(1, 100);
       $unitCost = $this->faker->randomFloat(2, 1, 500);
       $accumulatedQuantity = $this->faker->numberBetween(1, 1_000);
       $totalOperation = $this->faker->randomFloat(2, 1, 50_000);
       $accumulatedTotal = $this->faker->numberBetween(1, 10_000);
       $averagePrice = $this->faker->randomFloat(2, 1, 500);

       $position = new Position();
       $position
           ->setAsset($asset)
           ->setSequence($sequence)
           ->setType($type)
           ->setDate($date)
           ->setQuantity($quantity)
           ->setUnitPrice($unitCost)
           ->setAccumulatedQuantity($accumulatedQuantity)
           ->setTotalOperation($totalOperation)
           ->setAccumulatedTotal($accumulatedTotal)
           ->setAveragePrice($averagePrice);

       self::assertEquals($asset, $position->getAsset());
       self::assertEquals($sequence, $position->getSequence());
       self::assertEquals($type, $position->getType());
       self::assertEquals($date, $position->getDate());
       self::assertEquals($quantity, $position->getQuantity());
       self::assertEquals($unitCost, $position->getUnitPrice());
       self::assertEquals($accumulatedQuantity, $position->getAccumulatedQuantity());
       self::assertEquals($totalOperation, $position->getTotalOperation());
       self::assertEquals($accumulatedTotal, $position->getAccumulatedTotal());
       self::assertEquals($averagePrice, $position->getAveragePrice());
       self::assertNull($position->getOperation());
    }
}
