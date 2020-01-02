<?php

namespace App\Models\Facebook\Messenger;

use App\Models\Facebook\Messenger;

class Label extends Messenger
{
    public function create($name)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
            ],
            'json'  => [
                'name' => $name,
            ],
        ];

        $response = $this->client->request('POST', 'me/custom_labels', $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function all()
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
                'fields'       => 'name',
            ],
        ];

        $response = $this->client->request('GET', 'me/custom_labels', $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function get($labelId)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
                'fields'       => 'name',
            ],
        ];

        $response = $this->client->request('GET', $labelId, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function delete($labelId)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
            ],
        ];

        $response = $this->client->request('DELETE', $labelId, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function associateToUser($labelId, $psId)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
            ],
            'json'  => [
                'user' => $psId,
            ],
        ];

        $response = $this->client->request('POST', $labelId . '/label', $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getUserLabel($psId)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
                'fields'       => 'name',
            ],
        ];

        $response = $this->client->request('GET', $psId . '/custom_labels', $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function deleteFromUser($labelId, $psId)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
                'user'         => $psId,
            ],
        ];

        $response = $this->client->request('DELETE', $labelId . '/label', $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}
