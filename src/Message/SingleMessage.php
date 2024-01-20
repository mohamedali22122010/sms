<?php
// src/Message/SingleMessage.php

namespace App\Message;

class SingleMessage
{
    private $phone_nubmer;
    private $message;

    public function __construct($phone_nubmer, $message)
    {
        $this->phone_nubmer = $phone_nubmer;
        $this->message = $message;
    }

    public function data(): array
    {
        return [
            'phone_nubmer' => $this->phone_nubmer,
            'message' => $this->message 
        ];
    }
}
