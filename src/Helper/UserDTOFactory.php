<?php

namespace App\Helper;

use App\DataTransferObject\UserDTO;

class UserDTOFactory implements DTOFactoryInterface
{
    public function makeDTO(string $json): UserDTO
    {
        $content = json_decode($json);

        return (new UserDTO())
            ->setEmail($content->email ?? null)
            ->setPassword($content->password ?? null);
    }
}