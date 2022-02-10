<?php

namespace Omniship\Grabitmk\Http;

class ValidateCredentialsResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function getData()
    {
        if(isset($this->data)){
            return $this->data;
        }
        return true;
    }

}
