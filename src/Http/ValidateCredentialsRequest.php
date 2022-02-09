<?php

namespace Omniship\Grabitmk\Http;
class ValidateCredentialsRequest extends AbstractRequest
{


    public function getData()
    {
    }

    public function sendData($data)
    {
        $data = [
            "client_id" => "a0d0b525aa7666231e0ad0492197ad6d",
            "client_secret" => "4582b8a909dd74a23830c62ad61887e3",
            "username" => "d.dimovski@cloucart.com",
            "password" => "1CLOUDcart#",
            "grant_type" => "password"
        ];
        return $this->createResponse($this->getClient()->SendRequest($data));
    }

    protected function createResponse($data)
    {
        return $this->response = new ValidateCredentialsResponse($this, $data);
    }

}
