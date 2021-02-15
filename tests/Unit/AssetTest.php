<?php

namespace App\Tests\Unit;

use App\Entity\Asset;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class AssetTest extends TestCase
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }
    
    public function testAsset_ShouldSetAndGetSuccessfully()
    {
        $types = [
            Asset::TYPE_STOCK,
            Asset::TYPE_FUTURE_CONTRACT
        ];

        $code = $this->faker->text(10);
        $type = $this->faker->randomElement($types);
        $description = $this->faker->text(255);

        $asset = new Asset();
        $asset
            ->setCode($code)
            ->setType($type)
            ->setDescription($description);

        $this->assertEquals($code, $asset->getCode());
        $this->assertEquals($type, $asset->getType());
        $this->assertEquals($description, $asset->getDescription());
    }
}
