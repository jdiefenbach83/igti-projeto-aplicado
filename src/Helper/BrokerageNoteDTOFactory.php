<?php

namespace App\Helper;

use App\DataTransferObject\BrokerageNoteDTO;
use App\DataTransferObject\OperationDTO;

class BrokerageNoteDTOFactory implements DTOFactoryInterface
{
    public function makeDTO(string $json): BrokerageNoteDTO
    {
        $content = json_decode($json, true);

        $operations = $this->makeOperations($content['operations'] ?? []);

        return (new BrokerageNoteDTO())
            ->setBrokerId($content['broker_id'] ?? null)
            ->setDate($content['date'] ?? null)
            ->setNumber($content['number'] ?? null)
            ->setTotalMoviments($content['total_moviments'] ?? null)
            ->setOperationalFee($content['operational_fee'] ?? null)
            ->setRegistrationFee($content['registration_fee'] ?? null)
            ->setEmolumentFee($content['emolument_fee'] ?? null)
            ->setIssPisCofins($content['iss_pis_cofins'] ?? null)
            ->setIrrfNormalTax($content['irrf_normal_tax'] ?? null)
            ->setIrrfDaytradeTax($content['irrf_daytrade_tax'] ?? null)
            ->setOperations($operations);
    }

    private function makeOperations(?array $operations): array
    {
        $operationsDTO = [];

        foreach ($operations as $operation) {
            $operationsDTO[] = (new OperationDTO())
                ->setType($operation['type'] ?? null)
                ->setAssetId($operation['asset_id'] ?? null)
                ->setQuantity($operation['quantity'] ?? null)
                ->setPrice($operation['price'] ?? null);
        }

        return $operationsDTO;
    }
}