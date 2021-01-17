<?php

namespace App\Tests\Unit;

use App\Entity\Broker;
use App\Entity\BrokerageNote;
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
    
    public function testBrokerageNote_ShouldSetAndGetSuccessfully()
    {
        $date = \DateTimeImmutable::createFromMutable($this->faker->dateTime());
        $number = $this->faker->numberBetween(1, 100_000);
        $movimentation_total = $this->faker->randomFloat(4, 1, 100_000);
        $operational_fee = $this->faker->randomFloat(4, 1, 100_000);
        $registration_fee = $this->faker->randomFloat(4, 1, 100_000);
        $emolument_fee = $this->faker->randomFloat(4, 1, 100_000);
        $iss_pis_cofins = $this->faker->randomFloat(4, 1, 100_000);
        $note_irrf_tax = $this->faker->randomFloat(4, 1, 100_000);

        $brokerage_note = new BrokerageNote();
        $brokerage_note
            ->setBroker($this->broker)
            ->setDate($date)
            ->setNumber($number)
            ->setMovimentationTotal($movimentation_total)
            ->setOperationalFee($operational_fee)
            ->setRegistrationFee($registration_fee)
            ->setEmolumentFee($emolument_fee)
            ->setIssPisCofins($iss_pis_cofins)
            ->setNoteIrrfTax($note_irrf_tax);

        $this->assertEquals($this->broker, $brokerage_note->getBroker());
        $this->assertEquals($date, $brokerage_note->getDate());
        $this->assertEquals($number, $brokerage_note->getNumber());
        $this->assertEquals($movimentation_total, $brokerage_note->getMovimentationTotal());
        $this->assertEquals($operational_fee, $brokerage_note->getOperationalFee());
        $this->assertEquals($registration_fee, $brokerage_note->getRegistrationFee());
        $this->assertEquals($emolument_fee, $brokerage_note->getEmolumentFee());
        $this->assertEquals($iss_pis_cofins, $brokerage_note->getIssPisCofins());
        $this->assertEquals($note_irrf_tax, $brokerage_note->getNoteIrrfTax());
    }
}
