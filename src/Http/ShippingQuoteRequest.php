<?php

namespace Omniship\Grabitmk\Http;
use Omniship\Grabitmk\Client;

class ShippingQuoteRequest extends AbstractRequest
{

    public function getData()
    {
        return [
            "pickup_city" => "Дебар",
            "delivery_city" => "Дебар",
            "product_id" => 1
        ];

        /*
                               "sender_id":0,
                               "pickup_city": "Дебар",
                               "pickup_address": "Скопска 1, Дебар",
                               "pickup_zip": "1250",
                               "pickup_hub_id": "",
                               "recipient_id": 2,
                               "delivery_city": "Karposh, Skopje",
                               "delivery_address": "Neveska 1 2/10",
                               "delivery_zip": "1001",
                               "delivery_hub_id": 24,
                               "product_id": 1,
                               "secured_shipment": 0,
                               "return_document": 0,
                               "weight": 11,
                               "volume": 0.003,
                               "cod_amount":1324,
                               "value_amount": 2323,
                               "shipping_payer": 2,
                               "redemption_payer": 2,
                               "return_document_payer": 2,
                               "insurance_payer": 2
        */
    }

    public function sendData($data)
    {
        $params = $this->parameters->all();
        $services = (new Client( $params['username'], $params['password'], $params['base_url'], '', "POST", '/orders/calculator' ));
        $services = $services->SendRequest( $this->getData() );
        return $this->createResponse($services);
    }

    protected function createResponse($data)
    {
        return $this->response = new ShippingQuoteResponse($this, $data);
    }
}
