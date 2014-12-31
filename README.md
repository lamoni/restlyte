RESTLyte
--------
RESTLyte is a simple REST client library implemented in PHP.


Examples
--------

```php
$rest = new RESTLyte(
    "https://spaceexample.io",  // Server
    "username",                 // Username
    "password",                 // Password
    false,                      // SSL Checking?
    [                           // Array of custom HTTP headers to send with the API call
        'Cache-Control: no-cache'
    ]
);

$response = $rest->get(
    '/api/space/device-management/devices', // API call
    'JSON',                                 // Response handler (RAW | JSON | XML)
    'application/vnd.net.juniper.space.device-management.devices+json;version=1' // "Accept: "
);

```


