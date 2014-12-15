RESTLyte
--------
RESTLyte is a simple REST client library implemented in PHP.


Examples
--------

Call REST API with HTTP Authentication, disable SSL checking, send custom HTTP headers, and then use the JSON decoder
after requesting JSON output from the server.
--------
```php
$rest = new RESTLyte(
    "https://spaceexample.io",
    "username",
    "password",
    false,
    [
        'Cache-Control: no-cache'
    ]
);

$response = $rest->get(
    '/api/space/device-management/devices',
    'JSON',
    'application/vnd.net.juniper.space.device-management.devices+json;version=1'
);

```


