<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Asset;
use App\Entity\BrokerageNote;
use App\Entity\Company;
use App\Entity\Good;
use App\Entity\Operation;
use App\Entity\Position;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class GoodTest extends TestCase
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

        $code = $this->faker->text(10);
        $assetType = $this->faker->randomElement(Asset::getTypes());

        $asset = new Asset();
        $asset
            ->setCode($code)
            ->setType($assetType)
            ->setCompany($company);

        return $asset;
    }

    public function testGood_shouldSetAndGetSuccessfully(): void
    {
        $asset = $this->buildAsset();
        $year = $this->faker->randomNumber(4);
        $cnpj = $asset->getCompany()->getCnpj();
        $description = $this->faker->text();
        $situationYearBefore = $this->faker->randomFloat(2, 1, 10_000);
        $situationCurrentYear = $this->faker->randomFloat(2, 1, 10_000);

        $good = new Good();
        $good
            ->setAsset($asset)
            ->setYear($year)
            ->setCnpj($cnpj)
            ->setDescription($description)
            ->setSituationYearBefore($situationYearBefore)
            ->setSituationCurrentYear($situationCurrentYear);

        self::assertEquals($asset, $good->getAsset());
        self::assertEquals($year, $good->getYear());
        self::assertEquals($cnpj, $good->getCnpj());
        self::assertEquals($description, $good->getDescription());
        self::assertEquals($situationYearBefore, $good->getSituationYearBefore());
        self::assertEquals($situationCurrentYear, $good->getSituationCurrentYear());
    }
}