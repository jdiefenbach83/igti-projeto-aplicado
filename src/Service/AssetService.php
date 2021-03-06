<?php

namespace App\Service;

use App\DataTransferObject\DTOInterface;
use App\Entity\Asset;
use App\Helper\AssetFactory;
use App\Repository\AssetRepositoryInterface;
use App\Repository\CompanyRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AssetService implements ServiceInterface
{
    /**
     * @var AssetRepositoryInterface
     */
    private AssetRepositoryInterface $assetRepository;
    /**
     * @var CompanyRepositoryInterface
     */
    private CompanyRepositoryInterface $companyRepository;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    private iterable $validationErrors;

    public function __construct(
        AssetRepositoryInterface $assetRepository,
        CompanyRepositoryInterface $companyRepository,
        ValidatorInterface $validator
    )
    {
        $this->assetRepository = $assetRepository;
        $this->companyRepository = $companyRepository;
        $this->validator = $validator;
    }

    public function getAll(): array {
        return $this->assetRepository->findAll();
    }

    public function getById(int $id): ?Asset
    {
        return $this->assetRepository->findById($id);
    }

    public function add(DTOInterface $dto): ?Asset
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $asset_entity = (new AssetFactory($this->companyRepository))->makeEntityFromDTO($dto);
        $this->assetRepository->add($asset_entity);

        return $asset_entity;
    }

    public function update(int $id, DTOInterface $dto): ?Asset
    {
        $existing_entity = $this->assetRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $asset_entity = (new AssetFactory($this->companyRepository))->makeEntityFromDTO($dto);

        $existing_entity->setCode($asset_entity->getCode());
        $existing_entity->setType($asset_entity->getType());
        $existing_entity->setCompany($asset_entity->getCompany());

        $this->assetRepository->update($existing_entity);

        return $existing_entity;
    }

    public function remove(int $id): void
    {
        $existing_entity = $this->assetRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $this->assetRepository->remove($existing_entity);
    }

    /**
     * @return iterable
     */
    public function getValidationErrors(): iterable
    {
        return $this->validationErrors;
    }
}