<?php

namespace Omniship\Grabitmk\Http;
use Infifni\GrabitmkApiClient\Client;


class ServicesRequest extends AbstractRequest
{


    protected function createResponse($data)
    {
        return $this->response = new ServicesResponse($this, $data);
    }

    public function getData()
    {
        return '';
    }

    public function sendData($data)
    {
        $services = (new Client($this->getClientId(),$this->getUsername(),$this->getPassword()))->exportServices();
        return $this->createResponse($services);
    }
}
