<?php

namespace Omniship\Grabitmk\Http;

use Carbon\Carbon;
use Omniship\Common\Bill\Create;

class CreateBillOfLadingResponse extends AbstractResponse
{
    /**
     * @return bool
     */
    public function getData()
    {
        if(empty($this->data)){
            return $this->error;
        }
        $data = collect($this->data);
        $payments = collect($data['payments']);
        $sum = $payments->sum('price');

        $result = new Create();
        $result->setBolId($data['serialNumber']);
        $result->setBillOfLadingSource($data['labelUrl']);
        $result->setBillOfLadingUrl($data['labelUrl']);
        $result->setBillOfLadingType($result::PDF);
        $result->setTotal($sum);
        return $result;
    }

}
