<?php
// src/MessageHandler/SingleMessageHandler.php

namespace App\MessageHandler;

use App\Message\SingleMessage;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpClient\CurlHttpClient;

class SingleMessageHandler
{
    public function __invoke(SingleMessage $message)
    {
        // Your logic to handle the message goes here
        $message_data = $message->data();
        $phone_number = $message_data['phone_nubmer'];
        $message = $message_data['message'];

        $client = new CurlHttpClient([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);
        
        $uuid = Uuid::v4();
        
        $response = $client->request(
            'POST',    
            'http://localhost:8585/send',
            ['json' => [
                'phone_number' => $phone_number,
                'message' => $message,
            ]]);

        // we can log what we want in case if we need to trace anything 
    }
}
