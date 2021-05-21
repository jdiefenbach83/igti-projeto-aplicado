<?php

namespace App\Service;

use App\DataTransferObject\DTOInterface;
use App\Entity\Company;
use App\Helper\CompanyFactory;
use App\Repository\CompanyRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CompanyService implements ServiceInterface
{
    /**
     * @var CompanyRepositoryInterface
     */
    private CompanyRepositoryInterface $companyRepository;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    private iterable $validationErrors;

    public function __construct(CompanyRepositoryInterface $companyRepository, ValidatorInterface $validator)
    {
        $this->companyRepository = $companyRepository;
        $this->validator = $validator;
    }

    public function getAll(): array {
        return $this->companyRepository->findAll();
    }

    public function getById(int $id): ?Company
    {
        return $this->companyRepository->findById($id);
    }

    public function add(DTOInterface $dto): ?Company
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $company_entity = (new CompanyFactory())->makeEntityFromDTO($dto);
        $this->companyRepository->save($company_entity);

        return $company_entity;
    }

    public function update(int $id, DTOInterface $dto): ?Company
    {
        $existing_entity = $this->companyRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return null;
        }

        $company_entity = (new CompanyFactory())->makeEntityFromDTO($dto);

        $existing_entity->setName($company_entity->getName());
        $existing_entity->setCnpj($company_entity->getCnpj());

        $this->companyRepository->save($existing_entity);

        return $existing_entity;
    }

    public function remove(int $id): void
    {
        $existing_entity = $this->companyRepository->findById($id);

        if (is_null($existing_entity)) {
            throw new \InvalidArgumentException();
        }

        $this->companyRepository->remove($existing_entity);
    }

    /**
     * @return iterable
     */
    public function getValidationErrors(): iterable
    {
        return $this->validationErrors;
    }
}