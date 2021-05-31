<?php

namespace App\Tests\Unit;

use App\Entity\Asset;
use App\Entity\Company;
use App\Entity\Consolidation;
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
            ->setMarketType(Asset::MARKET_TYPE_SPOT)
            ->setCompany($company);

        $negotiationType = $this->faker->randomElement(PreConsolidation::getNegotiationTypes());

        $result = $this->faker->randomFloat(4, 1, 100_000);

        $preConsolidation = new PreConsolidation();
        $preConsolidation
            ->setYear($year)
            ->setMonth($month)
            ->setAsset($asset)
            ->setAssetType($asset->getType())
            ->setNegotiationType($negotiationType)
            ->setMarketType($asset->getMarketType())
            ->setResult($result);

        self::assertEquals($year, $preConsolidation->getYear());
        self::assertEquals($month, $preConsolidation->getMonth());
        self::assertEquals($asset, $preConsolidation->getAsset());
        self::assertEquals($asset->getType(), $preConsolidation->getAssetType());
        self::assertEquals($negotiationType, $preConsolidation->getNegotiationType());
        self::assertEquals($asset->getMarketType(), $preConsolidation->getMarketType());
        self::assertEquals($result, $preConsolidation->getResult());
    }

    public function testAsset_shouldFailWhenSetAnIncorrectAssetType(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid asset type');

        $preConsolidation = new PreConsolidation();
        $preConsolidation
            ->setAssetType('TEST');
    }

    public function testAsset_shouldFailWhenSetAnIncorrectNegotiationType(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid negotiation type');

        $preConsolidation = new PreConsolidation();
        $preConsolidation
            ->setNegotiationType('TEST');
    }

    public function testAsset_shouldFailWhenSetAnIncorrectMarketType(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid market type');

        $preConsolidation = new PreConsolidation();
        $preConsolidation
            ->setMarketType('TEST');
    }
}
