<?php

namespace App\Http\Controllers;

use App\Helpers\GenerateSignature;
use App\Helpers\JengaApi;
use Carbon\Carbon;
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

    //method to check the mini statement
    public function generateMiniStatement(Request $request){
        $countryCode = 'KES';
        $accountId = $request->input('accountId');
        $endurl = 'account-test/v2/accounts/ministatement/'.$countryCode.'/'.$accountId;
        //sign the request
        $signature = self::signAccountBalance($accountId,$countryCode);
        //send the request to jenga
        $response = JengaApi::get($endurl,$signature);

        return $response;

    }

    //inqury on account
    public function accountInquiry(Request $request){
        $countryCode = 'KE';
        $accountId = $request->input('accountId');

        $endurl = 'account-test/v2/accounts/search/'.$countryCode.'/'.$accountId;
        //sign the request
        $signature = self::signAccountBalance($accountId,$countryCode);
        //send the request to jenga
        $response = JengaApi::get($endurl,$signature);

        return $response;
    }

    //move money within equity account
    public function moveMoneyWithinEquity(Request $request){
        $data = $request->toArray();
        $requestBody = $request->all();
        $endurl =  'transaction-test/v2/remittance';
        $sourceCountryCode = $data['source']['countryCode'];
        $transferAmount= $data['transfer']['amount'];
        $sourceAccountNo= $data['source']['name'];
        $transferReference= $data['transfer']['reference'];

        $signature = GenerateSignature::signInternalTransfer($sourceAccountNo,$transferAmount,$sourceCountryCode,$transferReference);

        $response  = JengaApi::post($endurl,$requestBody,$signature);

        return $response;


    }

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
