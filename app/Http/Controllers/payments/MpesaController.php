<?php

namespace App\Http\Controllers\payments;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MpesaController extends Controller
{
    public function generateAccessToken()
    {
        $consumerKey =  "VsMzPH1GQIohTBEOTIfg0TSujF46qam7";
        $consumerSecret = "RIUXYaBRr4IUDUGR";
        $headers = ['Content-Type:application/json; charset=utf8'];
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($result);

        $access_token = $result->access_token;

        //Log::info("Token=".$access_token);

        return $access_token;
    }

    public function myMpesaPassword()
    {
        $timestamp = Carbon::rawParse('now')->format('YmdHms');

        $passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        $BusinessShortCode = 174379;
        
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode.$passkey.$timestamp);

        return $lipa_na_mpesa_password;
    }

    public function mpesaSTKPush()
    {
        $curl_post_data = [
            'BusinessShortCode' => 174379,
            'Password' => $this->myMpesaPassword(),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => 1,
            'PartyA' => 254795822141,
            'PartyB' => 174379,
            'PhoneNumber' => 254795822141,
            'CallBackURL' => 'https://swahilisms.com/get/stk/metadata',
            'AccountReference' => "E-SHOP",
            'TransactionDesc' => "Testing stk push on sandbox"
        ];
        
        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generateAccessToken()));

        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);

        return $curl_response;
    }
}
