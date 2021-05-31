<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Asset;
use App\Entity\Company;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class AssetTest extends TestCase
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }
    
    public function testAsset_shouldSetAndGetSuccessfully(): void
    {
        $cnpj = $this->faker->text(18);
        $name = $this->faker->text(255);

        $company = new Company();
        $company
            ->setCnpj($cnpj)
            ->setName($name);

        $code = $this->faker->text(10);
        $type = $this->faker->randomElement(Asset::getTypes());
        $marketType = $this->faker->randomElement(Asset::getMarketTypes());

        $asset = new Asset();
        $asset
            ->setCode($code)
            ->setType($type)
            ->setMarketType($marketType)
            ->setCompany($company);

        self::assertEquals($code, $asset->getCode());
        self::assertEquals($type, $asset->getType());
        self::assertEquals($marketType, $asset->getMarketType());
        self::assertEquals($company, $asset->getCompany());
    }

    public function testAsset_shouldFailWhenSetAnIncorrectType(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid type');
        
        $asset = new Asset();
        $asset
            ->setType('TEST');
    }

    public function testAsset_shouldFailWhenSetAnIncorrectMarketType(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid market type');

        $asset = new Asset();
        $asset
            ->setMarketType('TEST');
    }
}
