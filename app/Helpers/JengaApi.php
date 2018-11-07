<?php

namespace App\Helpers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class  JengaApi {

    public static function generateToken(){
        $baseUrl = env('JENGA_ENDPOINT');
        $password = env('JENGA_PASSWORD');
        $username = env('JENGA_USERNAME');
        $key = env("JENGA_KEY");

        $requestBody = [
            'username' => $username,
            'password' => $password
        ];

        $client = new Client();

        try{
            $response = $client->post($baseUrl.'identity-test/v2/token',[
                'headers' => [
                    'Authorization' => 'Basic '.$key,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                'form_params' => $requestBody

            ]);
            return json_decode((string) $response->getBody(), true);


        } catch(BadResponseException $exception) {

            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);

        }


    }
}