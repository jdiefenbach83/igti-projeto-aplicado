<?php

namespace App\Helper;

interface DTOFactoryInterface
{
    public function makeDTO(string $json);
}