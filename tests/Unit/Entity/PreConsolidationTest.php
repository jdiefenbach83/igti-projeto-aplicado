<?php

namespace App\Tests\Unit;

use App\Entity\Asset;
use App\Entity\Company;
use App\Entity\Position;
use App\Entity\PreConsolidation;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class PreConsolidationTest extends TestCase
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

        $cnpj = $this->faker->text(18);
        $name = $this->faker->text(255);

        $company = new Company();
        $company
            ->setCnpj($cnpj)
            ->setName($name);

        $asset = (new Asset())
            ->setCode('ABCD1')
            ->setType(Asset::TYPE_STOCK)
            ->setCompany($company);

        $type = $this->faker->randomElement(PreConsolidation::getTypes());

        $quantity = $this->faker->numberBetween(1, 1000);
        $totalOperation = $this->faker->randomFloat(4, 1, 1_000);
        $totalCosts = $this->faker->randomFloat(4, 1, 1_000);

        $preConsolidation = new PreConsolidation();
        $preConsolidation
            ->setYear($year)
            ->setMonth($month)
            ->setAsset($asset)
            ->setType($type)
            ->setQuantity($quantity)
            ->setTotalOperation($totalOperation)
            ->setTotalCosts($totalCosts);

        self::assertEquals($year, $preConsolidation->getYear());
        self::assertEquals($month, $preConsolidation->getMonth());
        self::assertEquals($asset, $preConsolidation->getAsset());
        self::assertEquals($type, $preConsolidation->getType());
        self::assertEquals($quantity, $preConsolidation->getQuantity());
        self::assertEquals($totalOperation, $preConsolidation->getTotalOperation());
        self::assertEquals($totalCosts, $preConsolidation->getTotalCosts());
    }
}
