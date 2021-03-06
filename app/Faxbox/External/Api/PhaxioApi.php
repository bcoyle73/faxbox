<?php namespace Faxbox\External\Api;

use Phaxio\Phaxio;
use Phaxio\PhaxioOperationResult;

class PhaxioApi implements FaxInterface {

    protected $phaxio;
    protected $response;
    
    public function __construct(Phaxio $phaxio, Response $response)
    {
        $this->phaxio = $phaxio;
        $this->response = $response;
    }
    
    public function sendFax($to, $filenames = [], $options = [])
    {
        $response = $this->phaxio->sendFax($to, $filenames, $options);
        return $this->_parseResponse($response);
    }

    public function receiveFax()
    {

    }
    
    public function status($id)
    {
        $response = $this->phaxio->faxStatus($id);
        return $this->_parseResponse($response);
    }
    
    public function createPhone($areaCode, $callbackUrl = null)
    {
        $response = $this->phaxio->provisionNumber($areaCode, $callbackUrl);
        
        return $this->_parseResponse($response);
    }

    public function deletePhone($number)
    {
        $response = $this->phaxio->releaseNumber($number);

        return $this->_parseResponse($response);
    }
    
    public function download($id, $size)
    {
        return $this->phaxio->download($id, $size);
    }
    
    public function getAvailableAreaCodes()
    {
        $response = $this->phaxio->getAvailableAreaCodes();
        return $this->_parseResponse($response);
    }
    
    private function _parseResponse(PhaxioOperationResult $response)
    {
        $this->response->setStatus($response->succeeded());
        $this->response->setMessage($response->getMessage());
        $this->response->setData($response->getData());
        
        return $this->response;
    }
}