<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PostalCodeController extends Controller
{
    public function showForm()
    {
        return view('postal_code');
    }

    public function lookup(Request $request)
    {
        //check remove dash
        $postcode = str_replace('-', '', $request->input('postcode'));
        
        $client = new Client();
        
        $response = $client->get("https://zipcloud.ibsnet.co.jp/api/search?zipcode={$postcode}");
        
        $data = json_decode($response->getBody(), true);
        
        $encodedData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        return view('postal_code', ['data' => $encodedData]);
    }
}
