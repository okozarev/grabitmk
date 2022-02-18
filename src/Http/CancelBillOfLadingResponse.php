<?php
namespace Omniship\Grabitmk\Http;

class CancelBillOfLadingResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function getData()
    {
        if(is_null($this->data) || isset($this->data->Error)){
            return $this->error;
        }
        return $this->data;
    }

}
