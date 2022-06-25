<?php 

namespace App\lib\IgdbBundle\IgdbWrapper;

use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchWrapper 
{
    private $HttpClient;
    private $client;
    private $secret;
    private $grantType;
    private $url;

    public function __construct($client, $secret, $grant_type, $url , HttpClientInterface $HttpClient) 
    {
        $this->client = array_pop($client);
        $this->secret = array_pop($secret);
        $this->grantType = array_pop($grant_type);
        $this->url = array_pop($url);
        $this->HttpClient = $HttpClient;
    }

    public function auth() : array
    {
        $response =  $this->HttpClient->request(
            'POST',
            $this->url, 
            [
                'query' => [
                        'client_id' => $this->client,
                        'client_secret' => $this->secret,
                        'grant_type' => $this->grantType
                    ]
            ]
        );
           
        if($response->getStatusCode() != 200)
        {
            throw new Exception('auth innaccessible : '.$response->getStatusCode() != 200);
        }

        return($response->toArray());
    }
}