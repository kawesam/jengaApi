<?php

namespace App\Helpers;

class  GenerateSignature
{
    //generate signature fo internaltransfers
    public static function signInternalTransfer($sourceAccountNumber,$amount,$currencyCode,$reference){

        $plaintext =$sourceAccountNumber.$amount.$currencyCode.$reference;

        $fp = fopen(env('PRIVATE_KEY'), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key);

        openssl_sign($plaintext,$signature,$pkeyid,OPENSSL_ALGO_SHA256);
        $signature = urlencode( base64_encode( $signature ) );

        return $signature;
    }


}

