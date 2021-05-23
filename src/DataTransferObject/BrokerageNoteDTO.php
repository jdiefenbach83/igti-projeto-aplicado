<?php

namespace App\DataTransferObject;

use App\Validator\BrokerExists;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class BrokerageNoteDTO implements DTOInterface
{
    /**
     * @var mixed
     */
    private $broker_id;
    /**
     * @var mixed
     */
    private $date;
    /**
     * @var mixed
     */
    private $number;
    /**
     * @var mixed
     */
    private $total_moviments;
    /**
     * @var mixed
     */
    private $operational_fee;
    /**
     * @var mixed
     */
    private $registration_fee;
    /**
     * @var mixed
     */
    private $emolument_fee;
    /**
     * @var mixed
     */
    private $iss_pis_cofins;
    /**
     * @var mixed
     */
    private $irrfNormalTax;
    /**
     * @var mixed
     */
    private $irrfDaytradeTax;
    /**
     * @var array
     */
    private array $operations;
    /**
     * @var mixed
     */
    private $total_operations;

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

    public function getIrrfNormalTax()
    {
        return $this->irrfNormalTax;
    }

    public function getIrrfDaytradeTax()
    {
        return $this->irrfDaytradeTax;
    }

    /**
     * @param $irrfNormalTax
     * @return BrokerageNoteDTO
     */
    public function setIrrfNormalTax($irrfNormalTax): BrokerageNoteDTO
    {
        $this->irrfNormalTax = $irrfNormalTax;

        return $this;
    }

    /**
     * @param $irrfDaytradeTax
     * @return BrokerageNoteDTO
     */
    public function setIrrfDaytradeTax($irrfDaytradeTax): BrokerageNoteDTO
    {
        $this->irrfDaytradeTax = $irrfDaytradeTax;

        return $this;
    }

    /**
     * @return array
     */
    public function getOperations(): array
    {
        return $this->operations;
    }

    /**
     * @param array $operations
     * @return BrokerageNoteDTO
     */
    public function setOperations(array $operations): BrokerageNoteDTO
    {
        $this->operations = $operations;
        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('broker_id', new NotNull());
        $metadata->addPropertyConstraint('broker_id', new NotBlank());
        $metadata->addPropertyConstraint('broker_id', new BrokerExists());
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
        $metadata->addPropertyConstraint('irrfNormalTax', new NotBlank());
        $metadata->addPropertyConstraint('irrfNormalTax', new PositiveOrZero());
        $metadata->addPropertyConstraint('irrfDaytradeTax', new NotBlank());
        $metadata->addPropertyConstraint('irrfDaytradeTax', new PositiveOrZero());
    }
}