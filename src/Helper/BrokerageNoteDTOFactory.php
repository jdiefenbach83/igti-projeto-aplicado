<?php

namespace App\Helper;

use App\DataTransferObject\BrokerageNoteDTO;

class BrokerageNoteDTOFactory implements DTOFactoryInterface
{
    public function makeDTO(string $json)
    {
        $content = json_decode($json);

        return (new BrokerageNoteDTO())
            ->setBrokerId($content->broker_id ?? '')
            ->setDate($content->date ?? null)
            ->setNumber($content->number ?? null)
            ->setTotalMoviments($content->total_moviments ?? null)
            ->setOperationalFee($content->operational_fee ?? null)
            ->setRegistrationFee($content->registration_fee ?? null)
            ->setEmolumentFee($content->emolument_fee ?? null)
            ->setIssPisCofins($content->iss_pis_cofins ?? null)
            ->setNoteIrrfTax($content->note_irrf_tax ?? null);
    }
}