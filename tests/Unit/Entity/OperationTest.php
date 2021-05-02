<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Asset;
use App\Entity\BrokerageNote;
use App\Entity\Company;
use App\Entity\Operation;
use App\Entity\Position;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class OperationTest extends TestCase
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function buildAsset(): Asset
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

    public function testOperation_shouldSetAndGetSuccessfully(): void
    {
        $line = $this->faker->numberBetween(1, 10);
        $operationType = $this->faker->randomElement(Operation::getTypes());
        $asset = $this->buildAsset();
        $quantity = $this->faker->numberBetween(1, 100);
        $price = $this->faker->randomFloat(2, 1, 100);
        $brokerageNote = new BrokerageNote();
        $operationalFee = $this->faker->randomFloat(2, 1, 10);
        $registrationFee = $this->faker->randomFloat(2, 1, 10);
        $emolumentFee = $this->faker->randomFloat(2, 1, 10);
        $brokerage = $this->faker->randomFloat(2, 1, 10);
        $issPisCofins = $this->faker->randomFloat(2, 1, 10);

        $operation = new Operation(
            $line,
            $operationType,
            $asset,
            $quantity,
            $price,
            $brokerageNote
        );
        $operation
            ->setOperationalFee($operationalFee)
            ->setRegistrationFee($registrationFee)
            ->setEmolumentFee($emolumentFee)
            ->setBrokerage($brokerage)
            ->setIssPisCofins($issPisCofins);

        self::assertEquals($line, $operation->getLine());
        self::assertEquals($operationType, $operation->getType());
        self::assertEquals($asset, $operation->getAsset());
        self::assertEquals($quantity, $operation->getQuantity());
        self::assertEquals($price, $operation->getPrice());
        self::assertEquals($brokerageNote, $operation->getBrokerageNote());
        self::assertEquals($operationalFee, $operation->getOperationalFee());
        self::assertEquals($registrationFee, $operation->getRegistrationFee());
        self::assertEquals($emolumentFee, $operation->getEmolumentFee());
        self::assertEquals($brokerage, $operation->getBrokerage());
        self::assertEquals($issPisCofins, $operation->getIssPisCofins());
    }

    public function testOperation_shouldFailWhenTheConstructorIsSetAnIncorrectType(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid type');

        $line = $this->faker->numberBetween(1, 10);
        $operationType = $this->faker->text(10);
        $asset = $this->buildAsset();
        $quantity = $this->faker->numberBetween(1, 100);
        $price = $this->faker->randomFloat(2, 1, 100);
        $brokerageNote = new BrokerageNote();

        new Operation(
            $line,
            $operationType,
            $asset,
            $quantity,
            $price,
            $brokerageNote
        );
    }

    public function testOperation_shouldFailWhenSetAnIncorrectType(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid type');

        $line = $this->faker->numberBetween(1, 10);
        $operationType = $this->faker->randomElement(Operation::getTypes());
        $asset = $this->buildAsset();
        $quantity = $this->faker->numberBetween(1, 100);
        $price = $this->faker->randomFloat(2, 1, 100);
        $brokerageNote = new BrokerageNote();

        $operation = new Operation(
            $line,
            $operationType,
            $asset,
            $quantity,
            $price,
            $brokerageNote
        );

        $operation->setType('TEST');
    }
}