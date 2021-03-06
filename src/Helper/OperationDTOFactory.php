<?php

namespace App\Helper;

use App\DataTransferObject\BrokerageNoteDTO;
use App\DataTransferObject\OperationDTO;

class OperationDTOFactory implements DTOFactoryInterface
{
    public function makeDTO(string $json): OperationDTO
    {
        $content = json_decode($json, true);

        return (new OperationDTO())
            ->setType($content['type'] ?? null)
            ->setAssetId($content['asset_id'] ?? null)
            ->setQuantity($content['quantity'] ?? null)
            ->setPrice($content['price'] ?? null);
    }
}