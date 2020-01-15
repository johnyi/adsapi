<?php

namespace App\Models\Facebook;

use GuzzleHttp\Client;

class Messenger
{
    protected $accessToken;

    protected $client;

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->client = new Client(['base_uri' => 'https://graph.facebook.com/v5.0/']);
    }
}
