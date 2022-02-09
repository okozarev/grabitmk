<?php

namespace Omniship\Grabitmk\Http;
use Infifni\GrabitmkApiClient\Client;


class TrackingParcelRequest extends AbstractRequest
{

    public function getData()
    {
        return $this->getBolId();
    }

    public function sendData($data)
    {
        $data = [
            'AWB' => $data,
            'display_mode' => 5,
            'language' => $this->getLanguageCode()
        ];
        $services = (new Client($this->getClientId(),$this->getUsername(),$this->getPassword()))->trackAwb($data);
        return $this->createResponse($services);
    }

    protected function createResponse($data)
    {
        return $this->response = new TrackingParcelResponse($this, $data);
    }
}
