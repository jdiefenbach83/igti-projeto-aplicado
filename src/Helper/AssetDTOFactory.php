<?php

namespace App\Helper;

use App\DataTransferObject\AssetDTO;

class AssetDTOFactory implements DTOFactoryInterface
{
    public function makeDTO(string $json): AssetDTO
    {
        $content = json_decode($json);

        return (new AssetDTO())
            ->setCode($content->code ?? null)
            ->setType($content->type ?? null)
            ->setMarketType($content->market_type ?? null)
            ->setCompanyId($content->company_id ?? null);
    }
}