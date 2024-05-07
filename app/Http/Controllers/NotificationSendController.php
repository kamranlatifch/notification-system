<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Jobs\SendFCMNotification;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
class NotificationSendController extends Controller
{
    public function updateDeviceToken(Request $request)
    {
        Auth::user()->device_token =  $request->token;

        Auth::user()->save();

        return response()->json(['Token successfully stored.....']);
    }

public function sendNotification(Request $request)

    {
        $url = 'https://fcm.googleapis.com/fcm/send';


        $serverKey = 'AAAAPv34oeM:APA91bFRvM3CbJv4cmxIRhlaRG-1T1RKPN-KLH4dHEuNxTzQWm3Y7iAdJuRzCweeKUu2RfE5lPbm-75r-s5_ZbUfHpum_6gwLu7JQLIk12EgmEqMZk8aJLeyZSYyiRGuFJq5WwNiJEZA'; // ADD SERVER KEY HERE PROVIDED BY FCM


        $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        // $FcmToken = User::where('id', 54)->pluck('device_token');


        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        // dd($result);
       return redirect('home');
        // return view('home');
    }






// public function sendNotification(Request $request)
// {
//     $title = $request->title;
//     $body = $request->body;

//     $adminToken = User::where('id', 54)->pluck('device_token');
//      // Assuming role_id 1 is for admin
//     $fcmTokens =User::where('id', 54)->pluck('device_token');


//     if (Auth::user()->id==54) {

//         $this->sendFCMNotification($adminToken, $title, $body);
//     } else {


//         foreach ($fcmTokens as $token) {
//             SendFCMNotification::dispatch($token, $title, $body);
//         }
//     }


// }

// private function sendFCMNotification($token, $title, $body)
// {


//     $url = 'https://fcm.googleapis.com/fcm/send';
//     $serverKey = 'AAAAPv34oeM:APA91bFRvM3CbJv4cmxIRhlaRG-1T1RKPN-KLH4dHEuNxTzQWm3Y7iAdJuRzCweeKUu2RfE5lPbm-75r-s5_ZbUfHpum_6gwLu7JQLIk12EgmEqMZk8aJLeyZSYyiRGuFJq5WwNiJEZA';


//         $data = [
//             "registration_ids" => $token,
//             "notification" => [
//                 "title" => "New Notification",
//                 "body" => "Order Placed",
//             ]
//         ];
//         $encodedData = json_encode($data);

//         $headers = [
//             'Authorization:key=' . $serverKey,
//             'Content-Type: application/json',
//         ];

//         $ch = curl_init();

//         curl_setopt($ch, CURLOPT_URL, $url);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//         curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//         // Disabling SSL Certificate support temporarly
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
//         // Execute post
//         $result = curl_exec($ch);
//         if ($result === FALSE) {
//             die('Curl failed: ' . curl_error($ch));
//         }
//         // Close connection
//         curl_close($ch);
//         // FCM response
//         dd($result);
//         return view('home');
// }

}