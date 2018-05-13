<?php

namespace CarApi\Entities;

const CAR_API_URL = "https://www.carqueryapi.com/api/0.3/";

class RemoteApi
{

    public static function trimsForMake($make)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => CAR_API_URL . "/?cmd=getTrims&make=$make",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
            ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            die("cURL Error #:" . $err);
        }

        $decodedAnswer = json_decode($response);

        $trims = $decodedAnswer->Trims;
        return $trims;
    }
}