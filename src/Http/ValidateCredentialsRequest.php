<?php

namespace Omniship\Grabitmk\Http;
use Omniship\Grabitmk\Client;


class ValidateCredentialsRequest extends AbstractRequest
{


    public function getData()
    {
    }

    public function sendData($data)
    {
        $services = (new Client( $data['username'], $data['password'], $data['base_url'], '', "POST", '' )); //->SendRequest($data);
        $services = $services->SendRequest();
        return $this->createResponse($services);
    }

    protected function createResponse($data)
    {
        return $this->response = new ValidateCredentialsResponse($this, $data);
    }

}
