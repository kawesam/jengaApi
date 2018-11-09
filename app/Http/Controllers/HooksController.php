<?php

namespace App\Http\Controllers;

use App\Helpers\JengaApi;
use Illuminate\Http\Request;
use anlutro\LaravelSettings\Facade as Setting;


class HooksController extends Controller
{
    //

    //method to generate jenga APi token
    public function generateToken(){
        $token = JengaApi::generateToken();

        Setting::set('mpesa-api',$token['access_token']);

        Setting::save();
        return $token;
    }

    //method to check account balance
    public function checkAccountBalance(Request $request){
        $countryCode = 'KE';
        $accountId = $request->input('accountId');
        $endurl = 'account-test/v2/accounts/balances/'.$countryCode.'/'.$accountId;

        $signature = self::signAccountBalance($accountId,$countryCode);

        $response = JengaApi::get($endurl,$signature);

        return $response;

    }

    //method to generate signature for accountbalance
//    public function signAccountBalance($accountNo,$countryCode){
//        $plaintext = $accountNo.$countryCode;
//        $privateKey=openssl_get_privatekey(env('PRIVATE_KEY'));
//        $token = Setting::get('api-token.token');
//
//        openssl_sign($plaintext,$signature,$privateKey,OPENSSL_ALGO_SHA256);
////        $privateKey=env('PRIVATE_KEY');
//
//    }

    public function signAccountBalance($accountNo,$countryCode){

        $plaintext = $accountNo.$countryCode;

        $fp = fopen(env('PRIVATE_KEY'), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key);

        openssl_sign($plaintext,$signature,$pkeyid,OPENSSL_ALGO_SHA256);
        $signature = urlencode( base64_encode( $signature ) );

        return $signature;

    }
}
