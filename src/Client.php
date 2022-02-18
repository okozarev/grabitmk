<?php

namespace Omniship\Grabitmk;

use GuzzleHttp\Client AS HttpClient;
use App\Exceptions\Error;
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
    protected $url_path;
    protected $cust_id;
    protected $cust_name;

    const SERVICE_PRODUCTION_URL = '%s/';


    public function __construct($username, $password, $base_url, $barear_token)
    {
        $this->username = $username;
        $this->password = $password;
        $this->barear_token = $barear_token;
        $this->base_url = $base_url;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    public function LoginRequest(){
        try {
            $client = new HttpClient(['base_uri' => $this->base_url]);

            $data = [
                "client_id" => "a0d0b525aa7666231e0ad0492197ad6d",
                "client_secret" => "4582b8a909dd74a23830c62ad61887e3",
                "username" => $this->username,
                "password" => $this->password,
                "grant_type" => "password"
            ];

            $response = $client->request('POST', '/users/login', [
                'json' => $data,
                'headers' =>  [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            $resp = json_decode( $response->getBody()->getContents() );

            $this->barear_token = $resp->access_token;
            $this->cust_id = $resp->client_id;
            $this->cust_name = $resp->client_name;

            return $resp;

        } catch (\Exception $e) {
             $this->error = [
                'code' => $e->getCode(),
                'error' => $e->getMessage()
            ];
        }
    }

    public function SendRequest($data = [], $method = 'POST', $path = null){
        try {
            $login = $this->LoginRequest();

            if(isset($data['sender_id'])){
                $data['sender_id'] = $login->client_id;
            }
            //if only login requested - go to the ELSE part and move on
            if($path){
                $client = new HttpClient(['base_uri' => $this->base_url]);

                $response = $client->request($method, $path, [
                    'json' => $data,
                    'headers' =>  [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer '.$login->access_token
                    ]
                ]);

                $resp = json_decode($response->getBody()->getContents());
                if(empty($path)) {
                    $resp[0]->cust_id = $this->cust_id;
                    $resp[0]->cust_name = $this->cust_name;
                }
                return $resp;

            }else{
                return $login->access_token;
            }

        } catch (\Exception $e) {
           return $this->error = [
                'code' => $e->getCode(),
                'error' => $e->getResponse()->getBody()->getContents()
            ];
        }
    }

    public function getOffices(){
        $offices = $this->SendRequest('', 'GET','droppoints');
        return $offices;

    }

}
