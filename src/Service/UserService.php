<?php

namespace App\Service;

use App\DataTransferObject\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private UserRepository $userRepository;
    private UserPasswordEncoderInterface $userPasswordEncoder;
    private ValidatorInterface $validator;
    private iterable $validationErrors;
    private string $token;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder,
        ValidatorInterface $validator
    )
    {
        $this->userRepository = $userRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->validator = $validator;
        $this->validationErrors = [];
    }

    public function login(UserDTO $userDTO): bool
    {
        $errors = $this->validator->validate($userDTO);

        if (count($errors) > 0) {
            $this->validationErrors = $errors;
            return false;
        }

        $user = $this->findByEmail($userDTO->getEmail());

        if (!$user) {
            return false;
        }

        $isValidPassword = $this->userPasswordEncoder->isPasswordValid($user, $userDTO->getPassword());

        if (!$isValidPassword) {
            return false;
        }

        $this->token = JWT::encode(['email' => $user->getEmail()], 'key', 'HS256');

        return true;
    }

    public function findByEmail($email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * @return iterable
     */
    public function getValidationErrors(): iterable
    {
        return $this->validationErrors;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
}
