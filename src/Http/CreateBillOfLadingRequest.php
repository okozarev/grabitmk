<?php

namespace  Omniship\Grabitmk\Http;
use Omniship\Grabitmk\Client;


class CreateBillOfLadingRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData() {
        if($this->getPayer() == 'SENDER'){
            $payer = 1;
        } else{
            $payer = 2;
        }

        $data = [
                "sender_id" => 92518,
                "pickup_city" => "Дебар",
                "pickup_address" => "Скопска 1, Дебар",
                "pickup_zip" => "1250",
                "pickup_hub_id" => "",
                "recipient_id" => 2,
                "delivery_city" => "Karposh, Skopje",
                "delivery_address" => "Neveska 1 2/10",
                "delivery_zip" => "1001",
                "delivery_hub_id" => 24,
                "product_id" => 1,
                "secured_shipment" => 0,
                "return_document" => 0,
                "weight" => 11,
                "cod_amount" =>1324,
                "shipping_payer" => 2,
                "redemption_payer" => 2,
                "return_document_payer" => 2,
                "insurance_payer" => 2
            ];
        return $data;
    }

    public function sendData($data) {
        $params = $this->parameters->all();
        $services = (new Client( $params['username'], $params['password'], $params['base_url'], '', "POST", '/orders' ));
        $services = $services->SendRequest( $this->getData() );
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
