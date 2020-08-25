<?php
/**
 * Created by PhpStorm.
 * User: ashiq
 * Date: 11/11/2019
 * Time: 3:08 PM
 */

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Auth;
use Auth;
use Session;
use Carbon\Carbon;
// use App\Helpers\UserInfo;
use Intervention\Image\ImageManagerStatic as Image;

class UserInfo
{
    public function __construct()
    {

    }


    public static function smsAPI($receiver_number, $sms_text)
    {
        //$api = "https://api.mobireach.com.bd/SendTextMessage?Username=taxman&Password=Abcd@2020&From=TaxManBD&To=".$receiver_number."&Message=". urlencode($sms_text);
        //https://api.mobireach.com.bd/SendTextMessage?Username=bengalfish&Password=Windows@55&From=BENGAL FISH&To=8801725930131&Message=testmessage
        $api = "https://api.mobireach.com.bd/SendTextMessage?Username=bengalfish&Password=Windows@55&From=".urlencode('BENGAL FISH')."&To=".$receiver_number."&Message=". urlencode($sms_text);
        //$api = "https://api.mobireach.com.bd/SendTextMessage?Username=bengalfish&Password=Windows@55&From=".urlencode('BENGAL FISH')."&To=".$receiver_number."&Message=". urlencode($sms_text);


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ=="
            ),
        ));
        //dd($curl);
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }

}
