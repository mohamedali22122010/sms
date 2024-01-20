<?php
// src/MessageHandler/CampaignFileMessageHandler.php

namespace App\MessageHandler;

use Symfony\Component\Uid\Uuid;
use App\Message\CampaignFileMessage;
use Symfony\Component\HttpClient\CurlHttpClient;

class CampaignFileMessageHandler
{
    public function __invoke(CampaignFileMessage $message)
    {
        // Your logic to handle the message goes here
        $camapaign_information = $message->getCampaignInformation();
        $ignoreFirstLine = true;
        $csv_file = $camapaign_information['csv_file'];
        $message = $camapaign_information['message'];
        $rows = array();

        // read csv file 
        if (($handle = fopen($csv_file->getRealPath(), "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                $i++;
                if ($ignoreFirstLine && $i == 1) { continue; }
                $row = explode(',', $data[0]);
                // we can make a multi dimintion array to save first name , last name , email if it's required to sms api 
                if($row[2] ?? false) {
                    // add $row[2] as key to avoide doublications 
                    $rows[$row[2]] = [
                        'phone_number' => $row[2]
                    ];
                }
            }
            fclose($handle);
        }

        // chunk data to array each array contain less than 1000 record
        $chunks = array_chunk($rows, 999);

        $client = new CurlHttpClient([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);
        
        $uuid = Uuid::v4();
        
        foreach($chunks as $chunk) {
            $response = $client->request(
                'POST',    
                'http://localhost:8585/bulk',
                ['json' => [
                    'campaign_id' => $uuid,
                    'message' => $message,
                    'recipients' => $chunk,
                ]]);
        }

        // we can log what we want in case if we need to trace anything 
    }
}
