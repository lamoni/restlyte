<?php namespace Lamoni\RESTLyte\RESTLyteRequest;

class RESTLyteRequest
{

    protected $response;

    public function __construct($server, $verb, $path, $authCredentials, $accept, $verifySSLPeer, $HTTPHeaders, $CURLOptions)
    {

        $curl = curl_init();

        $requestHeaders = [
            "{$verb} {$path} HTTP/1.0",
            "Authorization: Basic {$authCredentials}"
        ];

        if ($accept !== "") {

            $requestHeaders[] = "Accept: {$accept}";

        }

        $requestHeaders = array_merge($requestHeaders, $HTTPHeaders);

        $defaultCURLOptions = [
            CURLOPT_URL => $server . "/" . ltrim($path, '/'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $requestHeaders,
            CURLOPT_SSL_VERIFYPEER => $verifySSLPeer,
            CURLOPT_HTTPGET => 1,
            CURLOPT_POST => 0
        ];

        if ($verb !== "GET") {

            $defaultCURLOptions[CURLOPT_HTTPGET] = 0;

            $defaultCURLOptions[CURLOPT_POST] = 1;

        }

        $defaultCURLOptions = $CURLOptions + $defaultCURLOptions;

        curl_setopt_array($curl, $defaultCURLOptions);

        $this->response = (string)curl_exec($curl);

        curl_close($curl);

    }

    public function getXMLResponse($options=0, $dataIsURL=false, $namespace="", $isPrefix=false)
    {
        $xmlResponse = new \SimpleXMLElement($this->response, $options, $dataIsURL, $namespace, $isPrefix);
        if (count(libxml_get_errors())) {
            return libxml_get_errors();
        }

        return $xmlResponse;

    }

    public function getJSONResponse($associative=false, $depth=512, $options=0)
    {

        return json_decode($this->response, $associative, $depth, $options);

    }



}