<?php

namespace App\Tests\Unit;

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
    
    public function testAsset_ShouldSetAndGetSuccessfully(): void
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

        self::assertEquals($code, $asset->getCode());
        self::assertEquals($type, $asset->getType());
        self::assertEquals($company, $asset->getCompany());
    }
}
