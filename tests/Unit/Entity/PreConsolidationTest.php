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
    
    public function testPreConsolidation_ShouldSetAndGetSuccessfully(): void
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

        $negotiationType = $this->faker->randomElement(PreConsolidation::getNegotiationTypes());

        $result = $this->faker->randomFloat(4, 1, 100_000);

        $preConsolidation = new PreConsolidation();
        $preConsolidation
            ->setYear($year)
            ->setMonth($month)
            ->setAsset($asset)
            ->setNegotiationType($negotiationType)
            ->setResult($result);

        self::assertEquals($year, $preConsolidation->getYear());
        self::assertEquals($month, $preConsolidation->getMonth());
        self::assertEquals($asset, $preConsolidation->getAsset());
        self::assertEquals($negotiationType, $preConsolidation->getNegotiationType());
        self::assertEquals($result, $preConsolidation->getResult());
    }

    public function testAsset_shouldFailWhenSetAnIncorrectNegotiationType(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid negotiation type');

        $preConsolidation = new PreConsolidation();
        $preConsolidation
            ->setNegotiationType('TEST');
    }
}
