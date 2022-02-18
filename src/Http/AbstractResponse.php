<?php


namespace Omniship\Grabitmk\Http;

use Omniship\Acscourier\Client;
use Omniship\Message\AbstractResponse AS BaseAbstractResponse;

class AbstractResponse extends BaseAbstractResponse
{

    protected $error;

    protected $errorCode;

    protected $client;


    /**
     * Get the initiating request object.
     *
     * @return AbstractRequest
     */
    public function getRequest()
    {
        return  $this->request;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        $message = null;
        $data = $this->data;
        if(is_array($data) && isset($data['error'])){
            $decode = json_decode($data['error'], true);
            $message = $decode['error_description'];
        }
        return $message;
    }

    /**
     * @return null|string
     */
    public function getCode()
    {
        if($this->getMessage() != null){
            return $this->data['code'];
        }
        return null;
    }

    /**
     * @return null|Client
     */
    public function getClient()
    {
        return $this->getRequest()->getClient();
    }

    /**
     * @param mixed $client
     * @return AbstractResponse
     */


}
