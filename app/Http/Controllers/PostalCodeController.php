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

        $apiKey = '2c815780-1582-11ee-9df1-cbf0c013a37d';
        
        $client = new Client();
        
        $response = $client->get("https://app.zipcodebase.com/api/v1/search", [
            'headers' => [
                'apikey' => $apiKey,
            ],
            'query' => [
                'codes'=>  $postcode,
                'country'=> 'jp',
            ],
        ]);
        
        $data = json_decode($response->getBody(), true);
        
        $encodedData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        return view('postal_code', ['data' => $encodedData]);
    }
}
