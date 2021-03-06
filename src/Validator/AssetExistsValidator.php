<?php

namespace App\Validator;

use App\Repository\AssetRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AssetExistsValidator extends ConstraintValidator
{
    /**
     * @var AssetRepositoryInterface
     */
    private AssetRepositoryInterface $assetRepository;

    /**
     * AssetExistsValidator constructor.
     * @param AssetRepositoryInterface $assetRepository
     */
    public function __construct(AssetRepositoryInterface $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $existingAsset = $this->assetRepository->findById($value);

        if ($existingAsset) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
