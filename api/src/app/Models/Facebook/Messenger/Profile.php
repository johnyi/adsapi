<?php

namespace App\Models\Facebook\Messenger;

use App\Models\Facebook\Messenger;

class Profile extends Messenger
{
    protected static $fields = [
        'get_started',
        'greeting',
        'ice_breakers',
        'persistent_menu',
        'whitelisted_domains',
        'account_linking_url',
        'home_url',
    ];

    public function get()
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
                'fields'       => implode(',', self::$fields),
            ],
        ];

        $response = $this->client->get('me/messenger_profile', $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function update($data)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
            ],
            'json'  => $data,
        ];

        $response = $this->client->post('me/messenger_profile', $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function delete($fields)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
            ],
            'json'  => [
                'fields' => $fields,
            ],
        ];

        $response = $this->client->delete('me/messenger_profile', $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}
