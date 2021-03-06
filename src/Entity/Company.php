<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\Timestampable;
use JsonSerializable;

class Company implements EntityInterface, JsonSerializable
{
    use Timestampable;

    private ?int $id;
    private string $cnpj;
    private string $name;
    private Collection $assets;

    public function __construct()
    {
        $this->assets = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCnpj(): string
    {
        return $this->cnpj;
    }

    /**
     * @param string $cnpj
     * @return Company
     */
    public function setCnpj(string $cnpj): Company
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Company
     */
    public function setName(string $name): Company
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getAssets(): Collection
    {
        return $this->assets;
    }

    /**
     * @param Collection $assets
     * @return Company
     */
    public function setAssets(Collection $assets): Company
    {
        $this->assets = $assets;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'cnpj' => $this->cnpj,
            'name' => $this->name,
            'assets' => $this->assets,
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => 'api/companies/' . $this->id
                ],
            ]
        ];
    }
}
