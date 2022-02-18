<?php

namespace Omniship\Grabitmk\Http;

use Omniship\Common\ShippingQuoteBag;

class ShippingQuoteResponse extends AbstractResponse
{
    public function getData()
    {

        $result = new ShippingQuoteBag();

        if (isset($this->data['error'])) {
            return collect($this->data['error']);
        }

        $data_collect = collect($this->data);
        $total_price = $data_collect->sum('price');
        $result->push([
            'id' => 1,
            'name' => $data_collect[0]->service_name,
            'description' => $data_collect[0]->description,
            'price' => (float)$total_price,
            'pickup_date' => null,
            'pickup_time' => null,
            'delivery_date' => null,
            'delivery_time' => null,
            'currency' => 'MKD',
            'tax' => null,
            'insurance' => null,
            'exchange_rate' => 1,
            'payer' => null,
            'allowance_fixed_time_delivery' => false,
            'allowance_cash_on_delivery' => true,
            'allowance_insurance' => true,
        ]);
        return $result;
    }
}
