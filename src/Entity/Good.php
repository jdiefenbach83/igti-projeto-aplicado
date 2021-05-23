<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;
use JsonSerializable;

class Good implements EntityInterface, JsonSerializable
{
    use Timestampable;

    private ?int $id;
    private Asset $asset;
    private int $year;
    private string $cnpj;
    private string $description;
    private float $situationYearBefore;
    private float $situationCurrentYear;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Asset
     */
    public function getAsset(): Asset
    {
        return $this->asset;
    }

    /**
     * @param Asset $asset
     * @return Good
     */
    public function setAsset(Asset $asset): Good
    {
        $this->asset = $asset;

        return $this;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return Good
     */
    public function setYear(int $year): Good
    {
        $this->year = $year;

        return $this;
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
     * @return Good
     */
    public function setCnpj(string $cnpj): Good
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Good
     */
    public function setDescription(string $description): Good
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return float
     */
    public function getSituationYearBefore(): float
    {
        return $this->situationYearBefore;
    }

    /**
     * @param float $situationYearBefore
     * @return Good
     */
    public function setSituationYearBefore(float $situationYearBefore): Good
    {
        $this->situationYearBefore = $situationYearBefore;

        return $this;
    }

    /**
     * @return float
     */
    public function getSituationCurrentYear(): float
    {
        return $this->situationCurrentYear;
    }

    /**
     * @param float $situationCurrentYear
     * @return Good
     */
    public function setSituationCurrentYear(float $situationCurrentYear): Good
    {
        $this->situationCurrentYear = $situationCurrentYear;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'asset_id' => $this->asset->getId(),
            'year' => $this->year,
            'cnpj' => $this->cnpj,
            'description' => $this->description,
            'situation_year_before' => $this->situationYearBefore,
            'situation_current_year' => $this->situationCurrentYear,
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => 'api/goods/' . $this->id
                ],
            ]
        ];
    }
}