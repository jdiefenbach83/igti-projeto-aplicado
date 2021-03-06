<?php

namespace App\Validator;

use App\Repository\CompanyRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CompanyExistsValidator extends ConstraintValidator
{
    /**
     * @var CompanyRepositoryInterface
     */
    private CompanyRepositoryInterface $CompanyRepository;

    /**
     * CompanyExistsValidator constructor.
     * @param CompanyRepositoryInterface $CompanyRepository
     */
    public function __construct(CompanyRepositoryInterface $CompanyRepository)
    {
        $this->CompanyRepository = $CompanyRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $existingCompany = $this->CompanyRepository->findById($value);

        if ($existingCompany) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
