<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Company;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }
    
    public function testCompany_ShouldSetAndGetSuccessfully(): void
    {
        $cnpj = $this->faker->text(18);
        $name = $this->faker->text(255);

        $company = new Company();
        $company
            ->setCnpj($cnpj)
            ->setName($name);

        self::assertEquals($cnpj, $company->getCnpj());
        self::assertEquals($name, $company->getName());
    }
}
