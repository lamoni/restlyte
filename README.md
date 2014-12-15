RESTLyte
--------
RESTLyte is a simple REST client library implemented in PHP.


Examples
--------
```php
$rest = new RESTLyte(
    "lamoni",
    "phpsux",
    "https://192.168.0.100",
    false
);

$result = $rest->get('/api/call/to/server');
```