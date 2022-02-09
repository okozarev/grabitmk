<?php

namespace Omniship\Grabitmk;

use App\Http\Controllers\Controller;
use Infifni\Grabitmk\Client as ApiClient;
use Omniship\Grabitmk\Lib\City;
use Omniship\Grabitmk\Http\AbstractRequest;
use Infifni\Grabitmk\Request\City as ApiCity;
use Omniship\Grabitmk\Lib\Street;
use Omniship\Helper\Collection;

class Client
{
    protected $username;
    protected $password;
    protected $client_id;
    protected $client_secret;
    protected $language;
    protected $error;

    public function __construct($username, $password, $client_id, $client_secret, $language)
    {
        $this->username = $username;
        $this->password = $password;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->language = $language;
    }

    protected $cityFields = [
        'bg' => [
            'city' => 'град',
            'fan_city_id' => 'id_localitate_fan',
            'county' => 'judet'
        ],
        'mk' => [
            'city' => 'град',
            'fan_city_id' => 'id_localitate_fan',
            'county' => 'judet'
        ],
        'en' => [
            'city' => 'city',
            'fan_city_id' => 'fan_city_id',
            'county' => 'county'
        ]
    ];

    protected $streetFileds = [
        'bg' => [
            'street_id' => 'id_strada',
            'county' => 'Държава',
            'city' => 'Град',
            'name' => 'улица',
            'zipcode' => 'cod_postal',
            'type' => 'тип'
        ],
        'mk' => [
            'street_id' => 'id_strada',
            'county' => 'Земја',
            'city' => 'Град',
            'name' => 'улица',
            'zipcode' => 'cod_postal',
            'type' => 'тип'
        ],
        'en' => [
            'street_id' => 'street_id',
            'county' => 'county',
            'city' => 'city',
            'name' => 'street',
            'zipcode' => 'zip_code',
            'type' => 'type'

        ]
    ];

    public function getCities($zone = null, $name = null, $report_type = null)
    {
        $collection = [];
        $cities =(new ApiClient($this->client_id,$this->username,$this->password))->city([ 'language' => $this->language]);
        if (!empty($cities) && !empty($cities)) {
            $collection = array_map(function($city) {
                return new City([
                    'id' => $city[$this->cityFields[$this->language]['fan_city_id']],
                    'name' => $city[$this->cityFields[$this->language]['city']],
                    'state' => $city[$this->cityFields[$this->language]['county']]
                ]);
            },$cities);
        }
        return new Collection($collection);
    }

    public function getStreet($state = null, $local = null){
        $collection = [];
        $streets = (new ApiClient($this->client_id, $this->username, $this->password))->streets(['judet' => $state, 'localitate' => $local, 'language' =>  $this->language]);
        $sttee_chunk = array_chunk($streets, 50);
        if(!empty($streets)) {
            $collection = array_map(function ($street) {
                return [
                    'id' => $street[$this->streetFileds[$this->language]['street_id']],
                    'name' => $street[$this->streetFileds[$this->language]['name']],
                    'zipcode' => $street[$this->streetFileds[$this->language]['zipcode']],
                    'county' => $street[$this->streetFileds[$this->language]['county']],
                    'city' => $street[$this->streetFileds[$this->language]['city']],
                    'type' => $street[$this->streetFileds[$this->language]['type']]
                ];
            }, $streets);
        }
        return new Collection($collection);
    }


    public function getError()
    {
        return $this->error;
    }
}
