<?php

namespace Omniship\Grabitmk;

use Omniship\Common\AbstractGateway;
use Omniship\Grabitmk\Http\CancelBillOfLadingRequest;
use Omniship\Grabitmk\Http\CreateBillOfLadingRequest;
use Omniship\Grabitmk\Http\GetPdfRequest;
use Omniship\Grabitmk\Http\ServicesRequest;
use Omniship\Grabitmk\Http\TrackingParcelRequest;
use Omniship\Grabitmk\Http\ShippingQuoteRequest;
use Omniship\Grabitmk\Http\ValidateCredentialsRequest;

class Gateway extends AbstractGateway
{

    private $name = 'Powered by GrabIT';
    const TRACKING_URL = '%s/order/status/%s';


    /**
     * @return stringc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'tracking_url' => '',
            'username' => '',
            'password' => '',
            'client_id' => 'a0d0b525aa7666231e0ad0492197ad6d',
            'client_secret' => '4582b8a909dd74a23830c62ad61887e3',
            'grant_type'=> 'password'
        );
    }

    public function getClientId(){
        return $this->getParameter('client_id');
    }

    public function setClientId($value){
        return $this->setParameter('client_id', $value);
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }


    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->getParameter('client_secret');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setClientSecret($value)
    {
        return $this->setParameter('client_secret', $value);
    }



    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->getParameter('endpoint');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setEndpoint($value)
    {
        return $this->setParameter('endpoint', $value);
    }

    /**
     * @param array|ShippingQuoteRequest $parameters
     * @return ShippingQuoteRequest
     */
    public function getQuotes($parameters = [])
    {
        if($parameters instanceof ShippingQuoteRequest) {
            return $parameters;
        }
        if(!is_array($parameters)) {
            $parameters = [];
        }
        return $this->createRequest(ShippingQuoteRequest::class, $this->getParameters() + $parameters);
    }


    /**
     * @param string $bol_id
     * @return TrackingParcelRequest
     */
    public function trackingParcel($bol_id)
    {
        return $this->createRequest(TrackingParcelRequest::class, $this->setBolId($bol_id)->getParameters());
    }

    public function getServices($parameters = []){
        return $this->createRequest(ServicesRequest::class, $parameters);
    }

    public function getClient()
    {
        return new Client( $this->getUsername(), $this->getPassword(), $this->getClientId(), $this->getLanguageCode() );
    }

    /**
     * @param array|CreateBillOfLadingRequest $parameters
     * @return CreateBillOfLadingRequest
     */
    public function createBillOfLading($parameters = [])
    {
        if ($parameters instanceof CreateBillOfLadingRequest) {
            return $parameters;
        }
        if (!is_array($parameters)) {
            $parameters = [];
        }
        return $this->createRequest(CreateBillOfLadingRequest::class, $this->getParameters() + $parameters);
    }

    public function cancelBillOfLading($bol_id)
    {
        $this->setBolId($bol_id);
        return $this->createRequest(CancelBillOfLadingRequest::class, $this->getParameters());
    }

    /**
     * @param $bol_id
     * @return GetPdfRequest
     */
    public function getPdf($bol_id)
    {
        return $this->createRequest(GetPdfRequest::class, $this->setBolId($bol_id)->getParameters());
    }

    public function validateCredentials(array $parameters = [], $test_mode = null)
    {
        return $this->createRequest(ValidateCredentialsRequest::class, $parameters);
    }

    public function supportsCashOnDelivery()
    {
        return true;
    }

    public function supportsCreateBillOfLading(){
        return true;
    }

    public function trackingUrl($provider_url, $parcel_id)
    {
        return sprintf(static::TRACKING_URL, [$provider_url, $parcel_id]);
    }
}
