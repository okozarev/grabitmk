<?php

namespace Omniship\Grabitmk\Http;

use Google\Service\CloudSearch\PushItem;
use Omniship\Grabitmk\Client;
use PhpParser\Node\Expr\Cast\Array_;

class ShippingQuoteRequest extends AbstractRequest
{

    public function getData()
    {

        if($this->getPayer() == 'SENDER'){
            $payer = 1;
        } else{
            $payer = 2;
        }


        $data = [];
        $data["pickup_city"] = "Дебар";
        $data["pickup_address"] = "Скопска 1, Дебар";
        $data["pickup_zip"] = "1250";
        //$data["pickup_hub_id"] = "";
        $data["recipient_id"] = 2;
        $data["delivery_city"] = "Karposh, Skopje";
        $data["delivery_address"] = "Neveska 1 2/10";
        $data["delivery_zip"] = "1001";
        //$data["delivery_hub_id"] = "";
        $data["product_id"] = 1;
        $data["return_document"] = 0;
        $data["weight"] = 11;
        $data["cod_amount"] =1324;
        $data["shipping_payer"] = $payer;
        $data["redemption_payer"] = 2;
        $data["return_document_payer"] = 2;
        $data["insurance_payer"] = 2;

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
        $services = (new Client( $params['username'], $params['password'], $params['base_url'] ));
        $services = $services->SendRequest( $this->getData(), 'POST', '/orders/calculator');
        return $this->createResponse($services);
    }

    protected function createResponse($data)
    {
        return $this->response = new ShippingQuoteResponse($this, $data);
    }
}
