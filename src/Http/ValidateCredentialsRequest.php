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
        $params = $this->parameters->all();
        $services = (new Client( $params['username'], $params['password'], $params['base_url'], '', "POST", '' ));
        $services = $services->SendRequest();
        return $this->createResponse($services);
    }

    protected function createResponse($data)
    {
        return $this->response = new ValidateCredentialsResponse($this, $data);
    }

}
