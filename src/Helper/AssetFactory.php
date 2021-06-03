<?php

namespace App\Helper;

use App\DataTransferObject\AssetDTO;
use App\Entity\Asset;
use App\Repository\CompanyRepositoryInterface;

class AssetFactory
{
    /**
     * @var CompanyRepositoryInterface
     */
    private CompanyRepositoryInterface $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param AssetDTO $dto
     * @return Asset
     */
    public function makeEntityFromDTO(AssetDTO $dto): Asset
    {
        return $this->makeEntity(
            $dto->getCode(),
            $dto->getType(),
            $dto->getMarketType(),
            $dto->getCompanyId()
        );
    }

    /**
     * @param string $code
     * @param string $type
     * @param string $marketType
     * @param int|null $companyId
     * @return Asset
     */
    public function makeEntity(string $code, string $type, string $marketType, ?int $companyId = null): Asset
    {
        $company = null;

        if (!is_null($companyId)) {
            $company = $this->companyRepository->findById($companyId);
        }

        return (new Asset())
            ->setCode($code)
            ->setType($type)
            ->setMarketType($marketType)
            ->setCompany($company);
    }
}
