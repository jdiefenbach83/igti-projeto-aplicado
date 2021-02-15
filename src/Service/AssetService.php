<?php

namespace App\Service;

use App\DataTransferObject\DTOInterface;
use App\Entity\Asset;
use App\Helper\AssetFactory;
use App\Repository\AssetRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AssetService implements ServiceInterface
{
    /**
     * @var AssetRepositoryInterface
     */
    private AssetRepositoryInterface $assetRepository;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    private iterable $validationErrors;

    public function __construct(AssetRepositoryInterface $assetRepository, ValidatorInterface $validator)
    {
        $this->assetRepository = $assetRepository;
        $this->validator = $validator;
    }

    public function getAll(): array {
        return $this->assetRepository->findAll();
    }

    public function getById(int $id): ?Asset
    {
        return $this->assetRepository->findById($id);
    }

    public function add(DTOInterface $asset_dto): ?Asset
    {
        $errors = $this->validator->validate($asset_dto);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $Asset_entity = (new AssetFactory())->makeEntity($asset_dto);
        $this->assetRepository->add($Asset_entity);

        return $Asset_entity;
    }

    public function update(int $id, DTOInterface $asset_dto): ?Asset
    {
        $existing_entity = $this->assetRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $errors = $this->validator->validate($asset_dto);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $asset_entity = (new AssetFactory())->makeEntity($asset_dto);

        $existing_entity->setCode($asset_entity->getCode());
        $existing_entity->setType($asset_entity->getType());
        $existing_entity->setDescription($asset_entity->getDescription());

        $this->assetRepository->add($existing_entity);

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