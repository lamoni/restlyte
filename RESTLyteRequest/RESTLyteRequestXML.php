<?php namespace Lamoni\RESTLyte\RESTLyteRequest;

/**
 * Class RESTLyteRequestXML
 * @package Lamoni\RESTLyte\RESTLyteRequest
 */
class RESTLyteRequestXML extends RESTLyteRequestAbstract
{

    /**
     * Parses our $this->response into a new SimpleXMLElement() object
     *
     * @param array $customArgs
     * @return mixed
     */
    public function getResponse(array $customArgs=[])
    {
        $defaultCustomArgs = [
            'options'=>0,
            'dataIsURL'=>false,
            'namespace'=>"",
            'isPrefix'=>false
        ];

        $customArgs = array_merge($defaultCustomArgs, $customArgs);

        extract($customArgs);

        try {
            $xmlResponse = @new \SimpleXMLElement($this->response, $options, $dataIsURL, $namespace, $isPrefix);

            if (count(libxml_get_errors())) {

                throw new \Exception("Unable to parse XML: " . libxml_get_errors());

            }
        }
        catch (\Exception $e) {
            throw new \Exception($this->response);
        }

        return $xmlResponse;

    }

}