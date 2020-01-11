<?php

require_once ('Telegram.php');
require_once ('TelegramErrorLogger.php');
require_once ('datiTelegarm.php');



$url = "https://www.ryanair.com/api/booking/v4/it-it/availability?ADT=1&CHD=0&DateIn=2020-04-14&DateOut=2020-04-09&Destination=BRI&Disc=0&INF=0&Origin=PSA&RoundTrip=true&TEEN=0&FlexDaysIn=2&FlexDaysBeforeIn=2&FlexDaysOut=2&FlexDaysBeforeOut=2&ToUs=AGREED&IncludeConnectingFlights=false";

$json = file_get_contents($url);

$array = json_decode($json, true);

//print_r($array['trips']);

$orariMail = "";
$orariTelegram = "";

foreach ($array['trips'] as $key=>$value) { 
        
        echo("<br>" . $value['origin'] . "<br>");
        $orariMail .= ("<br>" . $value['origin'] . "<br>");
        $orariTelegram .= ("\n" . $value['origin'] . "\n");
            foreach($value['dates'] as $key=>$value ){
                foreach ($value['flights'] as $key => $value) {
                    echo(str_replace("T", " ", substr($value['time'][0],0,16). "  "));
                    $orariMail .=(str_replace("T", " ", substr($value['time'][0],0,16). "  "));
                    $orariTelegram .=(str_replace("T", " ", substr($value['time'][0],0,16). "  "));
                    foreach($value['regularFare']['fares'] as $key=>$value)
                    {
                        echo(" Prezzo: " . $value['amount'] . "<br>");
                        $orariMail .=(" Prezzo: " . $value['amount'] . "<br>");
                        $orariTelegram .=(" Prezzo: " . $value['amount'] . "\n");
                        
                    }
                }

            };
    }

    echo ($orariMail);
    echo ($orariTelegram);


$telegram = new Telegram(BOT_API_KEY);



$telegram->getMe();
    
    $content = array('chat_id' => CHAT_ID_AR, 'text' => $orariTelegram, 'parse_mode' => 'HTML');

    
    
    $telegram->sendMessage($content);

    $content1 = array('chat_id' => CHAT_ID_CB, 'text' => $orariTelegram, 'parse_mode' => 'HTML');

    
    
    $telegram->sendMessage($content1);


   


?>