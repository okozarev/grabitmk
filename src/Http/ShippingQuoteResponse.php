<?php

namespace Omniship\Grabitmk\Http;
use Omniship\Common\ShippingQuoteBag;

class ShippingQuoteResponse extends AbstractResponse
{
    public function getData()
    {
        $result = new ShippingQuoteBag();

        foreach ($this->data as $data){
            $result->push([
                'id' => 1,
                'name' => $data->cust_name,
                'description' => null,
                'price' => (float)$data->price,
                'pickup_date' => null,
                'pickup_time' => null,
                'delivery_date' => null,
                'delivery_time' => null,
                'currency' => 'MKD',
                'tax' => null,
                'insurance' => 0,
                'exchange_rate' => 1,
                'payer' =>null,
                'allowance_fixed_time_delivery' => false,
                'allowance_cash_on_delivery' => true,
                'allowance_insurance' => true,
            ]);
        }
        return $result;
    }
}
