<?php namespace Lamoni\RESTLyte\RESTLyteRequest;

class RESTLyteRequestXML extends RESTLyteRequestAbstract
{

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

        $xmlResponse = new \SimpleXMLElement($this->response, $options, $dataIsURL, $namespace, $isPrefix);

        if (count(libxml_get_errors())) {

            return libxml_get_errors();

        }

        return $xmlResponse;

    }

}