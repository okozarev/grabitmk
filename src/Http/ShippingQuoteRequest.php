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
            if($this->getOtherParameters('send_type') == 'office'){
                $data['pickup_hub_id'] = $this->getOtherParameters('send_type') == 'office' ? $this->getSenderAddress()->getOffice()->getId() : '';
                $data["delivery_address"] = $this->getReceiverAddress()->getOffice()->getAddressString();
                $data["delivery_hub_id"] = $this->getReceiverAddress()->getOffice()->getId();
            }
            if(!empty($this->getOtherParameters('insurance_amount'))){
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
        $services = (new Client( $params['username'], $params['password'], $params['base_url'], ''));
        $services = $services->SendRequest( $this->getData(), "POST", '/orders/calculator' );
        return $this->createResponse($services);
    }

    protected function createResponse($data)
    {
        return $this->response = new ShippingQuoteResponse($this, $data);
    }
}
