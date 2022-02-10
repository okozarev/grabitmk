<?php

namespace Omniship\Grabitmk;

use GuzzleHttp\Client AS HttpClient;
use http\Client\Response;
use Omniship\Helper\Collection;

class Client
{
    protected $username;
    protected $password;
    protected $error;
    protected $base_url;
    protected $barear_token;
    protected $request_type;

    const SERVICE_PRODUCTION_URL = '%s/';


    public function __construct($username, $password, $base_url, $barear_token, $request_type)
    {
        $this->username = $username;
        $this->password = $password;
        $this->base_url = $base_url;
        $this->barear_token = $barear_token;
        $this->request_type = $request_type;
    }


    public function getClientId(){
        return '';
    }



    private function getProductionURL()
    {
        return sprintf($this->SERVICE_PRODUCTION_URL, [$this->base_url]);
    }



    public function getError()
    {
        return $this->error;
    }

    public function LoginRequest(){
        try {
            $client = new HttpClient(['base_uri' => $this->getProductionURL()]);


            $data = [
                "client_id" => "a0d0b525aa7666231e0ad0492197ad6d",
                "client_secret" => "4582b8a909dd74a23830c62ad61887e3",
                "username" => $this->username,
                "password" => $this->password,
                "grant_type" => "password"
            ];

            $response = $client->request('POST', '', [
                'json' => $data,
                'headers' =>  [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            $resp = json_decode($response->getBody()->getContents());

            dd($resp);

        } catch (\Exception $e) {
            $this->error = [
                'code' => $e->getCode(),
                'error' => $e->getError()->getResponse()->getBody()->getContents()
            ];
        }
    }


    public function SendRequest($data = []){
        try {

            $this::LoginRequest();

            $client = new HttpClient(['base_uri' => $this->getProductionURL()]);

            $response = $client->request($this->request_type, '', [
                'json' => $data,
                'headers' =>  [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$this->barear_token
                ]
            ]);

            return json_decode($response->getBody()->getContents());

        } catch (\Exception $e) {
            $this->error = [
                'code' => $e->getCode(),
                'error' => $e->getError()->getResponse()->getBody()->getContents()
            ];
        }
    }

}
