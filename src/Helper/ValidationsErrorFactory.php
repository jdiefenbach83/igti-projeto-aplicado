<?php


namespace App\Helper;


class ValidationsErrorFactory
{
    private $messages;

    public function __construct($messages)
    {
        $this->messages = $messages;
    }

    public function getMessages(): array
    {
        $msgs = [];

        foreach ($this->messages as $message){

            $msgs['validation_errors'][] = [
                'property' => $message->getPropertyPath(),
                'message' => $message->getMessage(),
            ];
        }

        return $msgs;
    }
}