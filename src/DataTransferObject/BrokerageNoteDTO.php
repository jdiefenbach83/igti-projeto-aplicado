<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class BrokerageNoteDTO implements DTOInterface
{
    private ?string $broker_id;
    private ?string $date;
    private ?string $number;
    private ?string $total_moviments;
    private ?string $operational_fee;
    private ?string $registration_fee;
    private ?string $emolument_fee;
    private ?string $iss_pis_cofins;
    private ?string $note_irrf_tax;

    /**
     * @return string
     */
    public function getBrokerId(): ?string
    {
        return $this->broker_id;
    }

    /**
     * @param string $broker_id
     * @return BrokerageNoteDTO
     */
    public function setBrokerId(?string $broker_id): BrokerageNoteDTO
    {
        $this->broker_id = $broker_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return BrokerageNoteDTO
     */
    public function setDate(?string $date): BrokerageNoteDTO
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return BrokerageNoteDTO
     */
    public function setNumber(?string $number): BrokerageNoteDTO
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getTotalMoviments(): ?string
    {
        return $this->total_moviments;
    }

    /**
     * @param string $total_moviments
     * @return BrokerageNoteDTO
     */
    public function setTotalMoviments(?string $total_moviments): BrokerageNoteDTO
    {
        $this->total_moviments = $total_moviments;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperationalFee(): ?string
    {
        return $this->operational_fee;
    }

    /**
     * @param string $operational_fee
     * @return BrokerageNoteDTO
     */
    public function setOperationalFee(?string $operational_fee): BrokerageNoteDTO
    {
        $this->operational_fee = $operational_fee;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegistrationFee(): ?string
    {
        return $this->registration_fee;
    }

    /**
     * @param string $registration_fee
     * @return BrokerageNoteDTO
     */
    public function setRegistrationFee(?string $registration_fee): BrokerageNoteDTO
    {
        $this->registration_fee = $registration_fee;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmolumentFee(): ?string
    {
        return $this->emolument_fee;
    }

    /**
     * @param string $emolument_fee
     * @return BrokerageNoteDTO
     */
    public function setEmolumentFee(?string $emolument_fee): BrokerageNoteDTO
    {
        $this->emolument_fee = $emolument_fee;
        return $this;
    }

    /**
     * @return string
     */
    public function getIssPisCofins(): ?string
    {
        return $this->iss_pis_cofins;
    }

    /**
     * @param string $iss_pis_cofins
     * @return BrokerageNoteDTO
     */
    public function setIssPisCofins(?string $iss_pis_cofins): BrokerageNoteDTO
    {
        $this->iss_pis_cofins = $iss_pis_cofins;
        return $this;
    }

    /**
     * @return string
     */
    public function getNoteIrrfTax(): ?string
    {
        return $this->note_irrf_tax;
    }

    /**
     * @param string $note_irrf_tax
     * @return BrokerageNoteDTO
     */
    public function setNoteIrrfTax(?string $note_irrf_tax): BrokerageNoteDTO
    {
        $this->note_irrf_tax = $note_irrf_tax;
        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('broker_id', new NotNull());
        $metadata->addPropertyConstraint('broker_id', new NotBlank());
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