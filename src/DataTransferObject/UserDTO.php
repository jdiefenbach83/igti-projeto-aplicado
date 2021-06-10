<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class UserDTO implements DTOInterface
{
    private $email;
    private $password;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return UserDTO
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return UserDTO
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('email', new NotBlank(null, null, false, 'trim'));
        $metadata->addPropertyConstraint('password', new NotBlank(null, null, false, 'trim'));
    }
}