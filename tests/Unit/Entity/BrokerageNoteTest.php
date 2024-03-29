<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Asset;
use App\Entity\Broker;
use App\Entity\BrokerageNote;
use App\Entity\Company;
use App\Entity\Operation;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class BrokerageNoteTest extends TestCase
{
    private Generator $faker;
    private Broker $broker;

    public function setUp(): void
    {
        $this->faker = Factory::create();

        $code = $this->faker->numberBetween(100000, 200000);
        $name = $this->faker->name();
        $cnpj = $this->faker->numberBetween(1, 99999999999999);
        $site = $this->faker->url();

        $this->broker = new Broker();
        $this->broker
            ->setCode($code)
            ->setName($name)
            ->setCnpj($cnpj)
            ->setSite($site);
    }

    public function testBrokerageNote_ShouldSetAndGetSuccessfully(): void
    {
        $date = \DateTimeImmutable::createFromMutable($this->faker->dateTime());
        $number = $this->faker->numberBetween(1, 100_000);
        $total_moviments = $this->faker->randomFloat(4, 1, 100_000);
        $operational_fee = $this->faker->randomFloat(4, 1, 100_000);
        $registration_fee = $this->faker->randomFloat(4, 1, 100_000);
        $emolument_fee = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage = $this->faker->randomFloat(4, 1, 100_000);
        $iss_pis_cofins = $this->faker->randomFloat(4, 1, 100_000);
        $irrfNormalTax = $this->faker->randomFloat(4, 1, 100_000);
        $irrfDaytradeTax = $this->faker->randomFloat(4, 1, 100_000);

        $brokerageNote = new BrokerageNote();
        $brokerageNote
            ->setBroker($this->broker)
            ->setDate($date)
            ->setNumber($number)
            ->setTotalMoviments($total_moviments)
            ->setOperationalFee($operational_fee)
            ->setRegistrationFee($registration_fee)
            ->setEmolumentFee($emolument_fee)
            ->setBrokerage($brokerage)
            ->setIssPisCofins($iss_pis_cofins)
            ->setIrrfNormalTax($irrfNormalTax)
            ->setIrrfDaytradeTax($irrfDaytradeTax);

        self::assertEquals($this->broker, $brokerageNote->getBroker());
        self::assertEquals($date, $brokerageNote->getDate());
        self::assertEquals($number, $brokerageNote->getNumber());
        self::assertEquals($total_moviments, $brokerageNote->getTotalMoviments());
        self::assertEquals($operational_fee, $brokerageNote->getOperationalFee());
        self::assertEquals($registration_fee, $brokerageNote->getRegistrationFee());
        self::assertEquals($emolument_fee, $brokerageNote->getEmolumentFee());
        self::assertEquals($brokerage, $brokerageNote->getBrokerage());
        self::assertEquals($iss_pis_cofins, $brokerageNote->getIssPisCofins());
        self::assertEquals($irrfNormalTax, $brokerageNote->getIrrfNormalTax());
        self::assertEquals($irrfDaytradeTax, $brokerageNote->getIrrfDaytradeTax());
    }

    public function testBrokerageNote_ShouldCalculareCorrectly(): void
    {
        $date = \DateTimeImmutable::createFromMutable($this->faker->dateTime());
        $number = $this->faker->numberBetween(1, 100_000);
        $total_moviments = $this->faker->randomFloat(4, 1, 100_000);
        $operational_fee = $this->faker->randomFloat(4, 1, 100_000);
        $registration_fee = $this->faker->randomFloat(4, 1, 100_000);
        $emolument_fee = $this->faker->randomFloat(4, 1, 100_000);
        $brokerage = $this->faker->randomFloat(4, 1, 100_000);
        $iss_pis_cofins = $this->faker->randomFloat(4, 1, 100_000);
        $irrfNormalTax = $this->faker->randomFloat(4, 1, 100_000);
        $irrfDaytradeTax = $this->faker->randomFloat(4, 1, 100_000);

        $brokerageNote = new BrokerageNote();
        $brokerageNote
            ->setBroker($this->broker)
            ->setDate($date)
            ->setNumber($number)
            ->setTotalMoviments($total_moviments)
            ->setOperationalFee($operational_fee)
            ->setRegistrationFee($registration_fee)
            ->setEmolumentFee($emolument_fee)
            ->setBrokerage($brokerage)
            ->setIssPisCofins($iss_pis_cofins)
            ->setIrrfNormalTax($irrfNormalTax)
            ->setIrrfDaytradeTax($irrfDaytradeTax);

        $total_fees = bcadd($operational_fee, $registration_fee, 4);
        $total_fees = bcadd($total_fees, $emolument_fee, 4);

        $total_costs = bcadd($total_fees, $brokerage, 4);
        $total_costs = bcadd($total_costs, $iss_pis_cofins, 4);
        $total_costs = bcadd($total_costs, $irrfNormalTax, 4);
        $total_costs = bcadd($total_costs, $irrfDaytradeTax, 4);

        $net_total = bcsub($total_moviments, $total_costs, 4);

        $result = bcsub($total_moviments, $total_fees, 4);
        $result = bcsub($result, $iss_pis_cofins, 4);
        $result = bcsub($result, $brokerage, 4);
        $result = bcsub($result, $irrfNormalTax, 4);
        $result = bcsub($result, $irrfDaytradeTax, 4);

        self::assertEquals($total_fees, $brokerageNote->getTotalFees());
        self::assertEquals($total_costs, $brokerageNote->getTotalCosts());
        self::assertEquals($net_total, $brokerageNote->getNetTotal());
        self::assertEquals($result, $brokerageNote->getResult());
    }

    public function testBrokerageNote_ShouldAddOperationSuccessfully(): void
    {
        $date = \DateTimeImmutable::createFromMutable($this->faker->dateTime());
        $number = $this->faker->numberBetween(1, 100_000);

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

        $brokerage_note = new BrokerageNote();
        $brokerage_note
            ->setBroker($this->broker)
            ->setDate($date)
            ->setNumber($number);

        $brokerage_note->addOperation(Operation::TYPE_BUY, $asset, 1, 1.50);
        $brokerage_note->addOperation(Operation::TYPE_BUY, $asset, 1, 1.55);

        self::assertCount(2, $brokerage_note->getOperations());
        self::assertEquals(-3.05, $brokerage_note->getTotalOperations());
    }

    public function testBrokerageNote_ShouldEditOperationSuccessfully(): void
    {
        $date = \DateTimeImmutable::createFromMutable($this->faker->dateTime());
        $number = $this->faker->numberBetween(1, 100_000);

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

        $brokerage_note = new BrokerageNote();
        $brokerage_note
            ->setBroker($this->broker)
            ->setDate($date)
            ->setNumber($number);

        $new_asset = (new Asset())
            ->setCode('ABCD2')
            ->setType(Asset::TYPE_STOCK)
            ->setCompany($company);

        $brokerage_note->addOperation(Operation::TYPE_BUY, $asset, 1, 1.50);
        $brokerage_note->addOperation(Operation::TYPE_BUY, $asset, 1, 2.0);
        $totalBeforeEdit = $brokerage_note->getTotalOperations();
        $brokerage_note->editOperation(1, Operation::TYPE_SELL, $new_asset, 2, 1.55);
        $totalAfterEdit = $brokerage_note->getTotalOperations();

        self::assertEquals(Operation::TYPE_SELL, $brokerage_note->getOperations()[0]->getType());
        self::assertEquals($new_asset, $brokerage_note->getOperations()[0]->getAsset());
        self::assertEquals(2, $brokerage_note->getOperations()[0]->getQuantity());
        self::assertEquals(1.55, $brokerage_note->getOperations()[0]->getPrice());
        self::assertEquals(-3.5, $totalBeforeEdit);
        self::assertEquals(1.1, $totalAfterEdit);
    }

    public function testBrokerageNote_ShouldRemoveOperationSuccessfully(): void
    {
        $date = \DateTimeImmutable::createFromMutable($this->faker->dateTime());
        $number = $this->faker->numberBetween(1, 100_000);

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

        $brokerage_note = new BrokerageNote();
        $brokerage_note
            ->setBroker($this->broker)
            ->setDate($date)
            ->setNumber($number);

        $brokerage_note->addOperation(Operation::TYPE_BUY, $asset, 10, 1.50);
        $brokerage_note->addOperation(Operation::TYPE_BUY, $asset, 20, 2.0);
        $totalBeforeRemove = $brokerage_note->getTotalOperations();
        $brokerage_note->removeOperation(1);
        $totalAfterRemove = $brokerage_note->getTotalOperations();

        self::assertEquals(-55, $totalBeforeRemove);
        self::assertEquals(-40, $totalAfterRemove);
    }

    private function buildBrokerageNoteToProvateValues(): BrokerageNote
    {
        $date = \DateTimeImmutable::createFromMutable($this->faker->dateTime());
        $number = $this->faker->numberBetween(1, 100_000);

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

        $brokerageNote = new BrokerageNote();
        $brokerageNote
            ->setBroker($this->broker)
            ->setDate($date)
            ->setNumber($number)
            ->setTotalMoviments(-10)
            ->setOperationalFee(3)
            ->setRegistrationFee(4)
            ->setEmolumentFee(1.5)
            ->setBrokerage(2.5)
            ->setIssPisCofins(1.35);

        $brokerageNote->addOperation(Operation::TYPE_BUY, $asset, 2, 1.50);
        $brokerageNote->addOperation(Operation::TYPE_BUY, $asset, 1, 2.0);
        $brokerageNote->addOperation(Operation::TYPE_BUY, $asset, 1, 5.0);

        return $brokerageNote;
    }

    public function testBrokerageNote_ShouldProrateValues(): void
    {
        $brokerageNote = $this->buildBrokerageNoteToProvateValues();

        $totalProratedOperationalFee = .0;
        $totalProratedRegistrationFee = .0;
        $totalProratedEmolumentFee = .0;
        $totalOfFees = .0;
        $totalProratedBrokerage = .0;
        $totalProratedIssPisCofins = .0;
        $totalOfCosts = .0;

        foreach ($brokerageNote->getOperations() as $operation) {
            $totalProratedOperationalFee = bcadd($totalProratedOperationalFee, $operation->getOperationalFee(), 4);
            $totalProratedRegistrationFee = bcadd($totalProratedRegistrationFee, $operation->getRegistrationFee(), 4);
            $totalProratedEmolumentFee = bcadd($totalProratedEmolumentFee, $operation->getEmolumentFee(), 4);
            $totalOfFees = bcadd($totalProratedOperationalFee, $totalProratedRegistrationFee,4);
            $totalOfFees = bcadd($totalOfFees, $totalProratedEmolumentFee,4);
            $totalProratedBrokerage = bcadd($totalProratedBrokerage, $operation->getBrokerage(), 4);
            $totalProratedIssPisCofins = bcadd($totalProratedIssPisCofins, $operation->getIssPisCofins(), 4);
            $totalOfCosts = bcadd($totalOfFees, $totalProratedBrokerage, 4);
            $totalOfCosts = bcadd($totalOfCosts, $totalProratedIssPisCofins, 4);
        }

        self::assertTrue($brokerageNote->hasOperationsCompleted());
        self::assertEquals($brokerageNote->getTotalOperations(), $brokerageNote->getTotalMoviments());
        self::assertEquals($totalProratedOperationalFee, $brokerageNote->getOperationalFee());
        self::assertEquals($totalProratedRegistrationFee, $brokerageNote->getRegistrationFee());
        self::assertEquals($totalProratedEmolumentFee, $brokerageNote->getEmolumentFee());
        self::assertEquals($totalOfFees, $brokerageNote->getTotalFees());
        self::assertEquals($totalProratedBrokerage, $brokerageNote->getBrokerage());
        self::assertEquals($totalProratedIssPisCofins, $brokerageNote->getIssPisCofins());
        self::assertEquals($totalOfCosts, $brokerageNote->getTotalCosts());
    }

    public function testBrokerageNote_ShouldZeroProrateValues(): void
    {
        $brokerageNote = $this->buildBrokerageNoteToProvateValues();
        $brokerageNote->removeOperation(3);

        foreach ($brokerageNote->getOperations() as $operation) {
            self::assertEquals(.0, $operation->getOperationalFee());
            self::assertEquals(.0, $operation->getRegistrationFee());
            self::assertEquals(.0, $operation->getEmolumentFee());
            self::assertEquals(.0, $operation->getTotalFees());
            self::assertEquals(.0, $operation->getBrokerage());
            self::assertEquals(.0, $operation->getIssPisCofins());
            self::assertEquals(.0, $operation->getTotalCosts());
        }
    }
}
