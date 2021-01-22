<?php

namespace App\DataTransferObject;

use App\Validator\Broker;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class BrokerageNoteDTO implements DTOInterface
{
    private $broker_id;
    private $date;
    private $number;
    private $total_moviments;
    private $operational_fee;
    private $registration_fee;
    private $emolument_fee;
    private $iss_pis_cofins;
    private $note_irrf_tax;

    public function getBrokerId()
    {
        return $this->broker_id;
    }

    /**
     * @param $broker_id
     * @return BrokerageNoteDTO
     */
    public function setBrokerId($broker_id): BrokerageNoteDTO
    {
        $this->broker_id = $broker_id;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param $date
     * @return BrokerageNoteDTO
     */
    public function setDate($date): BrokerageNoteDTO
    {
        $this->date = $date;
        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param $number
     * @return BrokerageNoteDTO
     */
    public function setNumber($number): BrokerageNoteDTO
    {
        $this->number = $number;
        return $this;
    }

    public function getTotalMoviments()
    {
        return $this->total_moviments;
    }

    /**
     * @param $total_moviments
     * @return BrokerageNoteDTO
     */
    public function setTotalMoviments($total_moviments): BrokerageNoteDTO
    {
        $this->total_moviments = $total_moviments;
        return $this;
    }

    public function getOperationalFee()
    {
        return $this->operational_fee;
    }

    /**
     * @param $operational_fee
     * @return BrokerageNoteDTO
     */
    public function setOperationalFee($operational_fee): BrokerageNoteDTO
    {
        $this->operational_fee = $operational_fee;
        return $this;
    }

    public function getRegistrationFee()
    {
        return $this->registration_fee;
    }

    /**
     * @param $registration_fee
     * @return BrokerageNoteDTO
     */
    public function setRegistrationFee($registration_fee): BrokerageNoteDTO
    {
        $this->registration_fee = $registration_fee;
        return $this;
    }

    public function getEmolumentFee()
    {
        return $this->emolument_fee;
    }

    /**
     * @param $emolument_fee
     * @return BrokerageNoteDTO
     */
    public function setEmolumentFee($emolument_fee): BrokerageNoteDTO
    {
        $this->emolument_fee = $emolument_fee;
        return $this;
    }

    public function getIssPisCofins()
    {
        return $this->iss_pis_cofins;
    }

    /**
     * @param $iss_pis_cofins
     * @return BrokerageNoteDTO
     */
    public function setIssPisCofins($iss_pis_cofins): BrokerageNoteDTO
    {
        $this->iss_pis_cofins = $iss_pis_cofins;
        return $this;
    }

    public function getNoteIrrfTax()
    {
        return $this->note_irrf_tax;
    }

    /**
     * @param $note_irrf_tax
     * @return BrokerageNoteDTO
     */
    public function setNoteIrrfTax($note_irrf_tax): BrokerageNoteDTO
    {
        $this->note_irrf_tax = $note_irrf_tax;
        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('broker_id', new NotNull());
        $metadata->addPropertyConstraint('broker_id', new NotBlank());
        $metadata->addPropertyConstraint('broker_id', new Broker());
        $metadata->addPropertyConstraint('date', new NotBlank());
        $metadata->addPropertyConstraint('date', new Date());
        $metadata->addPropertyConstraint('number', new NotBlank());
        $metadata->addPropertyConstraint('number', new Positive());
        $metadata->addPropertyConstraint('total_moviments', new NotBlank());
        $metadata->addPropertyConstraint('total_moviments', new NotNull());
        $metadata->addPropertyConstraint('total_moviments', new Type('numeric'));
        $metadata->addPropertyConstraint('operational_fee', new NotBlank());
        $metadata->addPropertyConstraint('operational_fee', new PositiveOrZero());
        $metadata->addPropertyConstraint('registration_fee', new NotBlank());
        $metadata->addPropertyConstraint('registration_fee', new PositiveOrZero());
        $metadata->addPropertyConstraint('emolument_fee', new NotBlank());
        $metadata->addPropertyConstraint('emolument_fee', new PositiveOrZero());
        $metadata->addPropertyConstraint('iss_pis_cofins', new NotBlank());
        $metadata->addPropertyConstraint('iss_pis_cofins', new PositiveOrZero());
        $metadata->addPropertyConstraint('note_irrf_tax', new NotBlank());
        $metadata->addPropertyConstraint('note_irrf_tax', new PositiveOrZero());
    }
}