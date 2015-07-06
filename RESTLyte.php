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

    protected $responseType;

    /**
     * Create an instance of RESTLyte given the parameters.
     *
     * @param $username
     * @param $password
     * @param $server
     * @param bool $verifySSLPeer
     * @param array $HTTPHeaders
     */
    public function __construct($server, $username, $password, $verifySSLPeer=true, $HTTPHeaders=[], $responseType='XML')
    {

        $this->setAuthCredentials($username, $password);

        $this->setServer($server);

        $this->setVerifySSLPeer($verifySSLPeer);

        $this->setHTTPHeaders($HTTPHeaders);

        $this->setResponseType($responseType);


    }

    /**
     * @return mixed
     */
    public function getResponseType()
    {
        return $this->responseType;
    }

    /**
     * @param mixed $responseType
     */
    public function setResponseType($responseType)
    {
        $this->responseType = $responseType;
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

        $this->authCredentials = base64_encode(
            "{$this->username}:{$this->password}"
        );

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
     * Base for REST requests
     *
     * @param $verb
     * @param $path
     * @param $accept
     * @param array $customCURLOptions
     * @return \Lamoni\RESTLyte\RESTLyteRequest\RESTLyteRequestAbstract
     */
    public function request($verb, $path, $accept, array $customCURLOptions=[])
    {

        $verb = strtoupper(
            trim(
                $verb
            )
        );

        $instantiateClass = "\\Lamoni\\RESTLyte\\RESTLyteRequest\\RESTLyteRequest{$this->getResponseType()}";

        $request = new $instantiateClass(
            $this->getServer(),
            $verb,
            $path,
            $this->getAuthCredentials(),
            $accept,
            $this->getVerifySSLPeer(),
            $this->getHTTPHeaders(),
            $customCURLOptions
        );

        try {
            return $request->getResponse();
        }
        catch( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $path
     * @param string $accept
     * @return \Lamoni\RESTLyte\RESTLyteRequest\RESTLyteRequestAbstract
     */
    public function get($path, $accept="")
    {
        try {
            return $this->request(
                'GET',
                $path,
                $accept
            );
        }
        catch( Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Launches a POST request to the server
     *
     * @param $path
     * @param $postData
     * @param string $accept
     * @return \Lamoni\RESTLyte\RESTLyteRequest\RESTLyteRequestAbstract
     */
    public function post($path, $postData, $accept="")
    {
        try {
            return $this->request(
                'POST',
                $path,
                $accept,
                [
                    CURLOPT_POSTFIELDS => $postData
                ]
            );
        }
        catch( Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Launches a PUT request to the server
     *
     * @param $path
     * @param $postData
     * @param string $accept
     * @return \Lamoni\RESTLyte\RESTLyteRequest\RESTLyteRequestAbstract
     */
    public function put($path, $postData, $accept="")
    {
        try {
            return $this->request(
                'PUT',
                $path,
                $accept,
                [
                    CURLOPT_POSTFIELDS => $postData
                ]
            );
        }
        catch( Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Launches a PATCH request to the server
     *
     * @param $path
     * @param $postData
     * @param string $accept
     * @return \Lamoni\RESTLyte\RESTLyteRequest\RESTLyteRequestAbstract
     */
    public function patch($path, $postData, $accept="")
    {
        try {
            return $this->request(
                'PATCH',
                $path,
                $accept,
                [
                    CURLOPT_POSTFIELDS => $postData
                ]
            );
        }
        catch( Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Launches a DELETE request to the server
     *
     * @param $path
     * @param $postData
     * @param string $accept
     * @return \Lamoni\RESTLyte\RESTLyteRequest\RESTLyteRequestAbstract
     */
    public function delete($path, $postData, $accept="")
    {
        try {
            return $this->request(
                'DELETE',
                $path,
                $accept,
                [
                    CURLOPT_POSTFIELDS => $postData
                ]
            );
        }
        catch( Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

}