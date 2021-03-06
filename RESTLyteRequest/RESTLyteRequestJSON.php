<?php namespace Lamoni\RESTLyte\RESTLyteRequest;

/**
 * Class RESTLyteRequestJSON
 * @package Lamoni\RESTLyte\RESTLyteRequest
 */
class RESTLyteRequestJSON extends RESTLyteRequestAbstract
{

    /**
     * Parses our $this->response into a JSON-decoded object
     *
     * @param array $customArgs
     * @return mixed
     */
    public function getResponse(array $customArgs=[])
    {
        $defaultCustomArgs = [
            'associative'=>false,
            'depth'=>512,
            'options'=>0
        ];

        $customArgs = array_merge($defaultCustomArgs, $customArgs);

        extract($customArgs);

        return json_decode($this->response, $associative, $depth, $options);
    }

}