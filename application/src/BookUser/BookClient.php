<?php

namespace App\BookUser;

use SoapClient;

class BookClient
{
    private SoapClient $soapClient;

    public function __construct(
        private string $wsdlPath
    )
    {
        $params = array(
            'location'=>'http://symf-web/book-service?wsdl',
            'uri' =>  'urn://symf-web/book-service.php?wsdl'  ,
            'trace'=>1,'cache_wsdl'=>WSDL_CACHE_NONE    );

        $this->soapClient =  new SoapClient($this->wsdlPath, $params);
    }

    public function getBookYr($id_array){
        return $this->soapClient->__soapCall('bookYear', $id_array);
    }

    public function getBookDetails($book){
        return $this->soapClient->__soapCall('bookDetails', [$book]);
    }
}