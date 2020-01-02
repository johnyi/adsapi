<?php

namespace App\Models\Facebook\Messenger;

use GuzzleHttp\Client;

class Profile
{
    protected static $fields = [
        'first_name',
        'last_name',
        'profile_pic',
        'locale',
        'timezone',
        'gender',
    ];

    protected $accessToken;

    protected $url;

    protected $client;

    public function __construct($psId, $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->url = sprintf('https://graph.facebook.com/%s?fields=%s&access_token=%s', $psId, implode(',', self::$fields), $accessToken);
        $this->client = new Client();
    }

    public function get()
    {
        $response = $this->client->request('GET', $this->url);

        return json_decode($response->getBody()->getContents(), true);
    }
}
