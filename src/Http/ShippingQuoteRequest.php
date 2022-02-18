<?php

namespace Omniship\Grabitmk\Http;

use Omniship\Grabitmk\Client;

class ShippingQuoteRequest extends AbstractRequest
{

    public function getData()
    {

        if ($this->getPayer() == 'SENDER') {
            $payer = 1;
        } else {
            $payer = 2;
        }

        $data = [];

        $data["pickup_city"] = $this->getSenderAddress()->getCity()->getName();
        $data['pickup_zip'] = $this->getSenderAddress()->getCity()->getPostCode();
        if ($this->getOtherParameters('send_type') == 'office') {
            $data['pickup_hub_id'] = $this->getOtherParameters('send_type') == 'office' ? $this->getSenderAddress()->getOffice()->getId() : '';
        }
        if ($this->getOtherParameters('send_type') == 'address') {
            $data['pickup_address'] = $this->getSenderAddress()->getStreet()->getName();
        }
        //office

        if (!empty($this->getReceiverAddress()->getOffice())) {
            $data["delivery_hub_id"] = $this->getReceiverAddress()->getOffice()->getId();
            $data['delivery_address'] = $this->getReceiverAddress()->getOffice()->getAddressString();
        } else {
            $get_street_name = !empty($this->getReceiverAddress()->getStreet()) ? $this->getReceiverAddress()->getStreet()->getName() : '';
            if (!empty($get_street_name)) {
                $street_number = !empty($this->getReceiverAddress()->getStreetNumber()) ? $this->getReceiverAddress()->getStreetNumber() : '';
                $rec_address = $get_street_name . ' ' . $street_number;
            } else {
                $rec_address = '';
            }
            $data['delivery_address'] = $rec_address;
        }
        if (!empty($this->getOtherParameters('insurance_amount'))) {
            $insurance = $this->getOtherParameters('insurance_amount');
        } else {
            $insurance = $this->getOtherParameters('cod_amount');
        }
        $data["delivery_city"] = $this->getReceiverAddress()->getCity()->getName();
        $data["delivery_zip"] = $this->getReceiverAddress()->getPostCode();
        $data["product_id"] = $this->getOtherParameters('type_product') == 'package' ? 1 : 2;
        $data["weight"] = $this->getWeight();
        $data["value_amount"] = $this->getOtherParameters('insurance') == 1 ? $insurance : 0;
        $data['insurance_payer'] = $data["value_amount"] > 0 ? 1 : 0;
        return $data;
    }

    public function sendData($data)
    {
        $params = $this->parameters->all();
        $services = (new Client($params['username'], $params['password'], $params['base_url'], ''));
        $services = $services->SendRequest($this->getData(), "POST", '/orders/calculator');
        return $this->createResponse($services);
    }

    protected function createResponse($data)
    {
        return $this->response = new ShippingQuoteResponse($this, $data);
    }
}
