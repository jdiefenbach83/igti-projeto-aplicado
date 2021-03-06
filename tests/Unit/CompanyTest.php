<?php

namespace App\Tests\Unit;

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
    
    public function testCompany_ShouldSetAndGetSuccessfully()
    {
        $cnpj = $this->faker->text(18);
        $name = $this->faker->text(255);

        $company = new Company();
        $company
            ->setCnpj($cnpj)
            ->setName($name);

        $this->assertEquals($cnpj, $company->getCnpj());
        $this->assertEquals($name, $company->getName());
    }
}
