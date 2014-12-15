<?php namespace Lamoni\RESTLyte\RESTLyteRequest;

/**
 * Class RESTLyteRequestAbstract
 * @package Lamoni\RESTLyte\RESTLyteRequest
 * @abstract
 */
abstract class RESTLyteRequestAbstract
{
    /**
     * Holds the cURL response from the server
     *
     * @var string
     */
    protected $response;

    /**
     * Holds our cURL resource
     *
     * @var resource
     */
    protected $curl;

    /**
     * @param $server
     * @param $verb
     * @param $path
     * @param $authCredentials
     * @param $accept
     * @param $verifySSLPeer
     * @param $HTTPHeaders
     * @param $CURLOptions
     */
    public function __construct($server, $verb, $path, $authCredentials, $accept, $verifySSLPeer, $HTTPHeaders, $CURLOptions)
    {

        $this->curl = curl_init();

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

        curl_setopt_array($this->curl, $defaultCURLOptions);

        $this->response = (string)curl_exec($this->curl);

        curl_close($this->curl);

    }

    /**
     * Our response driver does its custom parsing here
     *
     * @param array $customArgs
     * @return mixed
     */
    abstract public function getResponse(array $customArgs=[]);


}