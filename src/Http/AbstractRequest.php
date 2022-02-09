<?php

namespace Omniship\Grabitmk\Http;

use Omniship\Interfaces\RequestInterface;
use Omniship\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest implements RequestInterface
{

    protected function validateLanguage(){
        $language = $this->getLanguageCode();
        if(strtolower($language) != 'mk'){
            if( strtolower($language) == 'bg' )
                return 'bg';
            else
                return 'en';
        }
        return 'mk';
    }

    public function getClientId(){

        return $this->getParameter('client_id');
    }

    public function SetClientId($value){
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

    abstract protected function createResponse($data);

}
