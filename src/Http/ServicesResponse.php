<?php

namespace Omniship\Grabitmk\Http;

use Omniship\Common\ServiceBag;

class ServicesResponse extends AbstractResponse
{
    public function getData()
    {
        $result = new ServiceBag();
        if(!is_null($this->getCode())) {
            return $result;
        }

        if(!empty($this->data)) {
            foreach($this->data AS $services) {
                foreach($services as $service) {
                    $result->push([
                        'id' => $service,
                        'name' => $service,
                    ]);
                }
            }
        }
        return $result;
    }

}
