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
    
    public function testBroker_ShouldSetAndGetSuccessfully()
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

        $this->assertEquals($code, $broker->getCode());
        $this->assertEquals($name, $broker->getName());
        $this->assertEquals($cnpj, $broker->getCnpj());
        $this->assertEquals($site, $broker->getSite());
    }
}
