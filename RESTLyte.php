<?php namespace Lamoni\RESTLyte;

/**
 * Class RESTLyte
 * @package Lamoni\RESTLyte
 */
class RESTLyte
{
    protected $server;

    protected $username;

    protected $password;

    protected $authCredentials;

    protected $verifySSLPeer;

    protected $curl;

    protected $HTTPHeaders = [];

    /**
     * Create an instance of RESTLyte given the parameters.
     *
     * @param $username
     * @param $password
     * @param $server
     * @param bool $verifySSLPeer
     * @param array $HTTPHeaders
     */
    public function __construct($username, $password, $server, $verifySSLPeer=true, $HTTPHeaders=[])
    {

        $this->setAuthCredentials($username, $password);

        $this->setServer($server);

        $this->setVerifySSLPeer($verifySSLPeer);

        $this->setHTTPHeaders($HTTPHeaders);

    }

    /**
     * Returns the HTTP headers we've built for our requests.
     *
     * @return array
     */
    public function getHTTPHeaders()
    {
        return $this->HTTPHeaders;
    }

    /**
     * Sets the HTTP headers we've built for our requests.
     *
     * @param array $HTTPHeaders
     */
    public function setHTTPHeaders($HTTPHeaders)
    {
        $this->HTTPHeaders = $HTTPHeaders;
    }

    /**
     * Add an HTTP header and value to our requests
     *
     * @param $headerName
     * @param $headerValue
     */
    public function addHTTPHeader($headerName, $headerValue)
    {
        $this->HTTPHeaders[$headerName] = $headerValue;
    }

    /**
     * Gets $verifySSLPeer.
     *
     * @return bool
     */
    public function getVerifySSLPeer()
    {
        return $this->verifySSLPeer;
    }

    /**
     * Sets the $verifySSLPeer knob
     *
     * @param bool $verifySSLPeer
     */
    public function setVerifySSLPeer($verifySSLPeer)
    {
        $this->verifySSLPeer = $verifySSLPeer;
    }

    /**
     * Gets $username
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets $username
     *
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Gets $password
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets $password
     *
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns $authCredentials
     *
     * @return mixed
     */
    public function getAuthCredentials()
    {
        return $this->authCredentials;
    }

    /**
     * Sets $username, $password, and $authCredentials based on the previously mentioned variables
     *
     * @param $username
     * @param $password
     */
    public function setAuthCredentials($username, $password)
    {

        $this->setUsername($username);

        $this->setPassword($password);

        $this->authCredentials = base64_encode("{$this->username}:{$this->password}");

    }

    /**
     * Gets $server
     *
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Sets $server
     *
     * @param mixed $server
     */
    public function setServer($server)
    {
        $this->server = rtrim($server, '/');
    }

    /**
     * Builds the HTTP headers used in our requests
     *
     * @param $verb
     * @param $path
     * @param string $accept
     * @return array
     */
    public function buildRequestHeaders($verb, $path, $accept="")
    {

        $requestHeaders = [
            "{$verb} {$path} HTTP/1.0",
            "Authorization: Basic {$this->authCredentials}"
        ];

        if ($accept !== "") {

            $requestHeaders[] = "Accept: {$accept}";

        }

        $requestHeaders = array_merge($requestHeaders, $this->HTTPHeaders);

        return $requestHeaders;

    }

    /**
     * Initialize cURL, then set your passed and default options
     *
     * @param $verb
     * @param $path
     * @param $requestHeaders
     * @param array $customCURLOptions
     */
    public function initCURLOptions($verb, $path, $requestHeaders, $customCURLOptions=[])
    {

        $this->curl = curl_init();

        $curlOptions = [
            CURLOPT_URL => $this->server . "/" . ltrim($path, '/'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $requestHeaders,
            CURLOPT_SSL_VERIFYPEER => $this->verifySSLPeer,
            CURLOPT_HTTPGET => 1,
            CURLOPT_POST => 0
        ];

        if ($verb !== "GET") {

            $curlOptions[CURLOPT_HTTPGET] = 0;

            $curlOptions[CURLOPT_POST] = 1;

        }

        $curlOptions = $customCURLOptions + $curlOptions;

        curl_setopt_array($this->curl, $curlOptions);

    }

    /**
     * Base for REST requests
     *
     * @param $verb
     * @param $path
     * @param $accept
     * @param array $customCURLOptions
     * @return \SimpleXMLElement
     */
    public function request($verb, $path, $accept, array $customCURLOptions=[])
    {

        $verb = strtoupper(
            trim(
                $verb
            )
        );

        $requestHeaders = $this->buildRequestHeaders($verb, $path, $accept, $this->HTTPHeaders);

        $this->initCURLOptions($verb, $path, $requestHeaders, $customCURLOptions);

        $data = (string)curl_exec($this->curl);

        curl_close($this->curl);

        return simplexml_load_string($data);

    }

    /**
     * Launches a GET request to the server
     *
     * @param $path
     * @param string $accept
     * @return \SimpleXMLElement
     */
    public function get($path, $accept="")
    {

        return $this->request(
            'GET',
            $path,
            $accept
        );

    }

    /**
     * Launches a POST request to the server
     *
     * @param $path
     * @param $postData
     * @param string $accept
     * @return \SimpleXMLElement
     */
    public function post($path, $postData, $accept="")
    {
        return $this->request(
            'POST',
            $path,
            $accept,
            [
                CURLOPT_POSTFIELDS => $postData
            ]
        );

    }

    /**
     * Launches a PUT request to the server
     *
     * @param $path
     * @param $postData
     * @param string $accept
     * @return \SimpleXMLElement
     */
    public function put($path, $postData, $accept="")
    {
        return $this->request(
            'PUT',
            $path,
            $accept,
            [
                CURLOPT_POSTFIELDS => $postData
            ]
        );

    }

    /**
     * Launches a PATCH request to the server
     *
     * @param $path
     * @param $postData
     * @param string $accept
     * @return \SimpleXMLElement
     */
    public function patch($path, $postData, $accept="")
    {
        return $this->request(
            'PATCH',
            $path,
            $accept,
            [
                CURLOPT_POSTFIELDS => $postData
            ]
        );

    }

    /**
     * Launches a DELETE request to the server
     *
     * @param $path
     * @param $postData
     * @param string $accept
     * @return \SimpleXMLElement
     */
    public function delete($path, $postData, $accept="")
    {
        return $this->request(
            'DELETE',
            $path,
            $accept,
            [
                CURLOPT_POSTFIELDS => $postData
            ]
        );

    }

}