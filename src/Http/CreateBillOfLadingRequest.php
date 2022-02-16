<?php

namespace Omniship\Grabitmk\Http;

use Omniship\Grabitmk\Client;


class CreateBillOfLadingRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData()
    {
        if ($this->getPayer() == 'SENDER') {
            $payer = 1;
        } else {
            $payer = 2;
        }


        $data = [];
        $data['sender_id'] = '';
        $data["pickup_city"] = $this->getSenderAddress()->getCity()->getName();
        if($this->getOtherParameters('send_type') == 'address'){
            $data['pickup_address'] = $this->getOtherParameters('sender_address');
        }
        $data['pickup_zip'] = $this->getSenderAddress()->getcity()->getPostCode();
        if($this->getOtherParameters('send_type') == 'office'){
            $data['pickup_hub_id'] = $this->getSenderAddress()->getOffice()->getId();
        }
        $data["recipient_id"] = 2;
        if (!empty($this->getReceiverAddress()->getOffice())) {
            $data["delivery_address"] = $this->getReceiverAddress()->getOffice()->getAddressString();
            $data["delivery_hub_id"] = $this->getReceiverAddress()->getOffice()->getId();
        }
        $data["delivery_city"] = $this->getReceiverAddress()->getCity()->getName();
        $data["delivery_zip"] = $this->getReceiverAddress()->getPostCode();
        $data["delivery_address"] = $this->getReceiverAddress()->getText();
        $data["product_id"] = $this->getOtherParameters('type_product') == 'package' ? 1 : 2;
        $data["return_document"] = $this->getOtherParameters('return_documents');
        if ($data['return_document'] == 1) {
            $data["return_document_payer"] = 1;
        }
        if($this->getOtherParameters('insurance') == 1){
            $insurace = $this->getOtherParameters('insurance_amount');
        } else {
            $insurace = 0;
        }
        $data["weight"] = $this->getWeight();
        $data['cod_amount'] = $this->getCashOnDeliveryAmount();
        $data["value_amount"] = $insurace;
        $data['insurance_payer'] = $insurace > 0 ? "1" : "0";
        $data["shipping_payer"] =(string)$payer;
        $data["redemption_payer"] = (string)$payer;
        $data["insurance_payer"] = (string)$payer;
    //    dd($data);
        return $data;
    }

    public function sendData($data)
    {
        $params = $this->parameters->all();
        $services = (new Client($params['username'], $params['password'], $params['base_url'], '', "POST", '/orders'));
        $services = $services->SendRequest( $this->getData(), "POST", '/orders' );
        return $this->createResponse($services);
    }

    /**
     * @param $data
     * @return ShippingQuoteResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new CreateBillOfLadingResponse($this, $data);
    }

}
