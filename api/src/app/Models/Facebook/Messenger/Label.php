<?php

namespace App\Models\Facebook\Messenger;

use GuzzleHttp\Client;

class Label
{
    protected $client;

    protected $accessToken;

    public function __construct($accessToken)
    {
        $this->client = new Client();
        $this->accessToken = $accessToken;
    }

    public function create($name)
    {
        $url = sprintf('https://graph.facebook.com/v5.0/me/custom_labels?access_token=%s', $this->accessToken);

        $response = $this->client->request('POST', $url, ['json' => ['name' => $name]]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function all()
    {
        $url = sprintf('https://graph.facebook.com/v5.0/me/custom_labels?fields=name&access_token=%s', $this->accessToken);

        $response = $this->client->request('GET', $url);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function get($labelId)
    {
        $url = sprintf('https://graph.facebook.com/v5.0/%s?fields=name&access_token=%s', $labelId, $this->accessToken);

        $response = $this->client->request('GET', $url);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function delete($labelId)
    {
        $url = sprintf('https://graph.facebook.com/v5.0/%s?access_token=%s', $labelId, $this->accessToken);

        $response = $this->client->request('DELETE', $url);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function associateWithUser($labelId, $psId)
    {
        $url = sprintf('https://graph.facebook.com/v5.0/%s/label?access_token=%s', $labelId, $this->accessToken);

        $response = $this->client->request('POST', $url, ['json' => ['user' => $psId]]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getUserLabel($psId)
    {
        $url = sprintf('https://graph.facebook.com/v5.0/%s/custom_labels?fields=name&access_token=%s', $psId, $this->accessToken);

        $response = $this->client->request('GET', $url, ['json' => ['user' => $psId]]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function deleteFromUser($psId)
    {
        $url = sprintf('https://graph.facebook.com/v5.0/%s/label?user=%s&access_token=%s', $psId, $this->accessToken);

        $response = $this->client->request('DELETE', $url);

        return json_decode($response->getBody()->getContents(), true);
    }
}
