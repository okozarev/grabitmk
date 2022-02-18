<?php
namespace Omniship\Grabitmk\Http;


use Omniship\Grabitmk\Client;

class CancelBillOfLadingRequest extends AbstractRequest
{

    /**
     * @return array
     */
    public function getData() {
        return $this->getBolId();
    }

    /**
     * @param mixed $data
     * @return CancelBillOfLadingResponse
     */
    public function sendData($data) {
        $params = $this->parameters->all();
        $services = (new Client( $params['username'], $params['password'], $params['base_url'], ''));
        $datas['serialNumber'] = array();
        $datas['serialNumber'][] = $data;
        $services = $services->SendRequest($datas, "DELETE", '/orders');
        return $this->createResponse($services);
    }


    /**
     * @param $data
     * @return CancelBillOfLadingResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new CancelBillOfLadingResponse($this, $data);
    }

}
