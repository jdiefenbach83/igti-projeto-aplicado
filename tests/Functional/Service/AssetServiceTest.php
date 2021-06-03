<?php

namespace App\Tests\Functional\Service;

use App\DataTransferObject\AssetDTO;
use App\Entity\Asset;
use App\Service\AssetService;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AssetServiceTest extends KernelTestCase
{
    private ?AssetService $assetService;

    private Generator $faker;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->assetService = self::$container->get(AssetService::class);

        $this->faker = Factory::create();
    }

    public function getAssetsToAdd(): iterable
    {
        yield 'Asset AAAA' => [
            'code' => 'AAAA',
            'type' => Asset::TYPE_STOCK,
            'market_type' => Asset::MARKET_TYPE_SPOT,
        ];
        yield 'Asset BBBB' => [
            'code' => 'BBBB',
            'type' => Asset::TYPE_STOCK,
            'market_type' => Asset::MARKET_TYPE_SPOT,
        ];
        yield 'Asset CCCC' => [
            'code' => 'CCCC',
            'type' => Asset::TYPE_DOLAR,
            'market_type' => Asset::MARKET_TYPE_FUTURE,
        ];
    }

    /**
     * @dataProvider getAssetsToAdd
     * @param string $assetCode
     * @param string $assetType
     * @param string $assetMarketType
     */
    public function testAssetService_shouldFindByCode(string $assetCode, string $assetType, string $assetMarketType): void
    {
        $assetDTO = new AssetDTO();
        $assetDTO
            ->setCode($assetCode)
            ->setType($assetType)
            ->setMarketType($assetMarketType);

        $this->assetService->add($assetDTO);

        $asset = $this->assetService->getByCode($assetCode);

        self::assertEquals($assetCode, $asset->getCode());
        self::assertEquals($assetType, $asset->getType());
        self::assertEquals($assetMarketType, $asset->getMarketType());
    }
}
