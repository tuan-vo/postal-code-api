# Postal code investigation using postcode-jp

## JSON data:
```
{
    "query": {
        "codes": [
            "1500043"
        ],
        "country": "jp"
    },
    "results": {
        "1500043": [
            {
                "postal_code": "150-0043",
                "country_code": "JP",
                "latitude": 35.6575,
                "longitude": 139.6982,
                "city": "Dogenzaka",
                "state": "Tokyo To",
                "city_en": "Dogenzaka",
                "state_en": "Tokyo To",
                "state_code": "40"
            }
        ]
    }
}
```

## Implementation steps:
Step 1: Create an account and login (https://app.zipcodestack.com/)

Step 2: Install the Guzzle HTTP package
```
composer require guzzlehttp/guzzle
```
Step 3: Create route and controller
1. in routes/web.php
```
Route::get('/postal-code', [PostalCodeController::class, 'showForm'])->name('postal_code.form');
Route::get('/postal-code/lookup', [PostalCodeController::class, 'lookup'])->name('postal_code.lookup');
```
2. create controller:
```
php artisan make:controller PostalCodeController
```
3. in app/Http/controllers/PostalCodeController.php:
```
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
        
        $response = $client->get(
            'https://api.zipcodestack.com/v1/search',
            [
                'headers' => [
                    'apikey' => '01H40JGENKQPCNJHWNZ064TX1X',
                ],
                'query' => [
                    'codes'=>  $postcode,
                    'country'=> 'jp',
                ],
            ]
        );
        
        $data = json_decode($response->getBody(), true);
        
        $encodedData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        return view('postal_code', ['data' => $encodedData]);
    }
}


```
Step 4: Create lookup interface
1. Create resources/views/postal_code.blade.php
2. In resources/views/postal_code.blade.php:
```
<!DOCTYPE html>
<html>
<head>
    <title>Postal Code Lookup</title>
</head>
<body>
    <h1>Postal Code Lookup</h1>
    
    <form action="{{ route('postal_code.lookup') }}" method="GET">
        <label for="postcode">Enter Postal Code:</label>
        <input type="text" id="postcode" name="postcode" required>
        <button type="submit">Lookup</button>
    </form>
    
    @if(isset($data))
        <h2>Result:</h2>
        <pre>{{ $data }}</pre>
    @endif
</body>
</html>

```
----------------------------------
Document:  https://zipcodestack.com/documentation/
