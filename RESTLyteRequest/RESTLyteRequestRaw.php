<?php namespace Lamoni\RESTLyte\RESTLyteRequest;

/**
 * Class RESTLyteRequestRaw
 * @package Lamoni\RESTLyte\RESTLyteRequest
 */
class RESTLyteRequestRaw extends RESTLyteRequestAbstract
{

    /**
     * Returns $this->response with no processing applied
     *
     * @param array $customArgs
     * @return mixed
     */
    public function getResponse(array $customArgs=[])
    {

        return $this->response;

    }

}