<?php

namespace Omniship\Grabitmk\Http;


class TrackingParcelRequest extends AbstractRequest
{

    public function getData()
    {
        return [
            'ACSAlias' => 'ACS_Trackingsummary',
            'ACSInputParameters' => [
                'Company_ID' => $this->getCompanyId(),
                'Company_Password' => $this->getCompanyPassword(),
                'User_ID' => $this->getUsername(),
                'User_Password' => $this->getPassword(),
                'Voucher_No' => $this->getBolId(),
                'Language' => 'EN'
                ]
            ];
    }

    public function sendData($data)
    {
        return $this->createResponse($this->getClient()->SendRequest($data));
    }

    protected function createResponse($data)
    {
        return $this->response = new TrackingParcelResponse($this, $data);
    }
}
