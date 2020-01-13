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

        $response = $this->client->post('me/custom_labels', $options);

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

        $response = $this->client->get('me/custom_labels', $options);

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

        $response = $this->client->get($labelId, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function delete($labelId)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
            ],
        ];

        $response = $this->client->delete($labelId, $options);

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

        $response = $this->client->post($labelId . '/label', $options);

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

        $response = $this->client->get($psId . '/custom_labels', $options);

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

        $response = $this->client->delete($labelId . '/label', $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}
