<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Asset;
use App\Entity\Company;
use App\Entity\Good;
use App\Repository\AssetRepository;
use App\Repository\AssetRepositoryInterface;
use App\Repository\CompanyRepository;
use App\Repository\CompanyRepositoryInterface;
use App\Repository\GoodRepository;
use App\Repository\GoodRepositoryInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GoodRespositoryTest extends KernelTestCase
{
    private $companyRepository;
    private $assetRepository;
    private $goodRepository;

    private Generator $faker;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->companyRepository = self::$container->get(CompanyRepository::class);
        $this->assetRepository = self::$container->get(AssetRepository::class);
        $this->goodRepository = self::$container->get(GoodRepository::class);

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

        $this->companyRepository->save($company);

        $code = $this->faker->text(10);
        $type = $this->faker->randomElement(Asset::getTypes());

        $asset = new Asset();
        $asset
            ->setCode($code)
            ->setType($type)
            ->setCompany($company);

        $this->assetRepository->save($asset);

        return $asset;
    }

    private function buildGood(): Good
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

        return $good;
    }

    public function testGoodRepository_shouldGetListSuccessfully(): void
    {
        $expectedCount = 1;

        $good = $this->buildGood();

        $this->goodRepository->save($good);
        $goodList = $this->goodRepository->findAll();

        self::assertCount($expectedCount, $goodList);
    }

    public function testGoodRepository_shouldSaveSuccessfully(): void
    {
        $good = $this->buildGood();

        $this->goodRepository->save($good);
        $goodList = $this->goodRepository->findAll();

        self::assertEquals($good, $goodList[0]);
    }

    public function testGoodRepository_shouldRemoveSuccessfully(): void
    {
        $expectedCount = 0;

        $good = $this->buildGood();

        $this->goodRepository->save($good);
        $this->goodRepository->remove($good);

        $goodList = $this->goodRepository->findAll();

        self::assertCount($expectedCount, $goodList);
    }
}