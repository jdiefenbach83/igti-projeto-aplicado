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
    private $assetService;

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
        ];
        yield 'Asset BBBB' => [
            'code' => 'BBBB',
            'type' => Asset::TYPE_STOCK,
        ];
        yield 'Asset CCCC' => [
            'code' => 'CCCC',
            'type' => Asset::TYPE_FUTURE_CONTRACT,
        ];
    }

    /**
     * @dataProvider getAssetsToAdd
     * @param string $assetCode
     * @param string $assetType
     */
    public function testAssetService_shouldFindByCode(string $assetCode, string $assetType): void
    {
        $assetDTO = new AssetDTO();
        $assetDTO
            ->setCode($assetCode)
            ->setType($assetType);

        $this->assetService->add($assetDTO);

        $asset = $this->assetService->getByCode($assetCode);

        self::assertEquals($assetCode, $asset->getCode());
    }
}

