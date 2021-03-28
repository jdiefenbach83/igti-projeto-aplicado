<?php

namespace App\Tests\Unit;

use App\Entity\Broker;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class BrokerTest extends TestCase
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }
    
    public function testBroker_ShouldSetAndGetSuccessfully(): void
    {
        $code = $this->faker->numberBetween(100000, 200000);
        $name = $this->faker->name();
        $cnpj = $this->faker->numberBetween(1, 99999999999999);
        $site = $this->faker->url();

        $broker = new Broker();
        $broker
            ->setCode($code)
            ->setName($name)
            ->setCnpj($cnpj)
            ->setSite($site);

        self::assertEquals($code, $broker->getCode());
        self::assertEquals($name, $broker->getName());
        self::assertEquals($cnpj, $broker->getCnpj());
        self::assertEquals($site, $broker->getSite());
    }
}
