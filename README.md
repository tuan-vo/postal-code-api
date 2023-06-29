# Postal code investigation using zipcloud

## JSON data:
```
{
    "message": null,
    "results": [
        {
            "address1": "東京都",
            "address2": "千代田区",
            "address3": "千代田",
            "kana1": "ﾄｳｷｮｳﾄ",
            "kana2": "ﾁﾖﾀﾞｸ",
            "kana3": "ﾁﾖﾀﾞ",
            "prefcode": "13",
            "zipcode": "1000001"
        }
    ],
    "status": 200
}
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
        
        $response = $client->get("https://zipcloud.ibsnet.co.jp/api/search?zipcode={$postcode}");
        
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
Document:  http://zipcloud.ibsnet.co.jp/doc/api
