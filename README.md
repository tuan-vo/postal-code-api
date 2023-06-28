# Postal code investigation using postcode-jp

## JSON data:
```
[
    {
        "prefCode": "13",
        "cityCode": "101",
        "postcode": "1000001",
        "oldPostcode": "100",
        "pref": "東京都",
        "city": "千代田区",
        "town": "千代田",
        "allAddress": "東京都千代田区千代田",
        "hiragana": {
            "pref": "とうきょうと",
            "city": "ちよだく",
            "town": "ちよだ",
            "allAddress": "とうきょうとちよだくちよだ"
        },
        "halfWidthKana": {
            "pref": "ﾄｳｷｮｳﾄ",
            "city": "ﾁﾖﾀﾞｸ",
            "town": "ﾁﾖﾀﾞ",
            "allAddress": "ﾄｳｷｮｳﾄﾁﾖﾀﾞｸﾁﾖﾀﾞ"
        },
        "fullWidthKana": {
            "pref": "トウキョウト",
            "city": "チヨダク",
            "town": "チヨダ",
            "allAddress": "トウキョウトチヨダクチヨダ"
        },
        "generalPostcode": true,
        "officePostcode": false,
        "location": {
            "latitude": 35.683799743652344,
            "longitude": 139.7539520263672
        }
    }
]
```

## Implementation steps:
Step 1: Install the Guzzle HTTP package
```
composer require guzzlehttp/guzzle
```
Step 2: Create route and controller
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
        
        $response = $client->get("https://apis.postcode-jp.com/api/v5/postcodes/{$postcode}");
        
        $data = json_decode($response->getBody(), true);
        
        $encodedData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        return view('postal_code', ['data' => $encodedData]);
    }
}
```
Step 3: Create lookup interface
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
Document:  https://api-doc.postcode-jp.com/#api-v5
