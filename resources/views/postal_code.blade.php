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
