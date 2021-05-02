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

        $negotiationType = $this->faker->randomElement(PreConsolidation::getNegotiationTypes());

        $result = $this->faker->randomFloat(4, 1, 100_000);
        $negativeResultLastMonth = $this->faker->randomFloat(4, 1, 100_000);
        $calculationBasis = $this->faker->randomFloat(4, 1, 100_000);
        $lossToCompensate = $this->faker->randomFloat(4, 1, 100_000);
        $withholdingTax = $this->faker->randomFloat(4, 1, 100_000);
        $taxRate = $this->faker->randomFloat(4, 1, 100_000);
        $taxDue = $this->faker->randomFloat(4, 1, 100_000);

        $preConsolidation = new PreConsolidation();
        $preConsolidation
            ->setYear($year)
            ->setMonth($month)
            ->setAsset($asset)
            ->setNegotiationType($negotiationType)
            ->setResult($result)
            ->setNegativeResultLastMonth($negativeResultLastMonth)
            ->setCalculationBasis($calculationBasis)
            ->setLossToCompensate($lossToCompensate)
            ->setWithholdingTax($withholdingTax)
            ->setTaxRate($taxRate)
            ->setTaxDue($taxDue);

        self::assertEquals($year, $preConsolidation->getYear());
        self::assertEquals($month, $preConsolidation->getMonth());
        self::assertEquals($asset, $preConsolidation->getAsset());
        self::assertEquals($negotiationType, $preConsolidation->getNegotiationType());
        self::assertEquals($result, $preConsolidation->getResult());
        self::assertEquals($negativeResultLastMonth, $preConsolidation->getNegativeResultLastMonth());
        self::assertEquals($calculationBasis, $preConsolidation->getCalculationBasis());
        self::assertEquals($lossToCompensate, $preConsolidation->getLossToCompensate());
        self::assertEquals($withholdingTax, $preConsolidation->getWithholdingTax());
        self::assertEquals($taxRate, $preConsolidation->getTaxRate());
        self::assertEquals($taxDue, $preConsolidation->getTaxDue());
    }
}
