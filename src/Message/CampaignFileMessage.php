<?php
// src/Message/CampaignFileMessage.php

namespace App\Message;

class CampaignFileMessage
{
    private $csv_file;
    private $message;

    public function __construct($csv_file, $message)
    {
        $this->csv_file = $csv_file;
        $this->message = $message;
    }

    public function getCampaignInformation(): array
    {
        return [
            'csv_file' => $this->csv_file,
            'message' => $this->message 
        ];
    }
}
