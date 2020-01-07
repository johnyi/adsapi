<?php

namespace App\Models\Facebook;

use GuzzleHttp\Client;

class Messenger
{
    protected $client;

    protected $accessToken;

    public function __construct($accessToken)
    {
        $this->client = new Client(['base_url' => 'https://graph.facebook.com/v5.0/']);
        $this->accessToken = $accessToken;
    }
}
