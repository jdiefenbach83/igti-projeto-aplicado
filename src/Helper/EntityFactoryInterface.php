<?php


namespace App\Helper;


interface EntityFactoryInterface
{
    public function makeEntity(string $json);
}