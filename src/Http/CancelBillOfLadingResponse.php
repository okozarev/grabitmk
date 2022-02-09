<?php

namespace Omniship\Grabitmk\Http;

use Infifni\GrabitmkApiClient\Exception\GrabitmkInstanceException;

class CancelBillOfLadingResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function getData()
    {
        if((int)$this->data == 0) {
            return false;
        }
        return $this->data;
    }

}
