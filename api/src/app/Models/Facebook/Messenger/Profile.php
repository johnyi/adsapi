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
        $response = [];

        $options = [
            'query' => [
                'access_token' => $this->accessToken,
                'fields'       => implode(',', self::$fields),
            ],
        ];

        $profile = json_decode($this->client->get('me/messenger_profile', $options)->getBody()->getContents(), true);
        if (!empty($profile['data'])) {
            $response['getStarted'] = $profile['get_started'];
            $response['greeting'] = $profile['greeting'];
            $response['iceBreakers'] = $profile['ice_breakers'];
            $response['persistentMenu'] = $profile['persistent_menu'];
            $response['whitelistedDomains'] = $profile['whitelisted_domains'];
            $response['accountLinkingUrl'] = $profile['account_linking_url'];
            $response['homeUrl'] = $profile['home_url'];
        }

        return $response;
    }

    public function update($data)
    {
        $response = [];

        $options = [
            'query' => [
                'access_token' => $this->accessToken,
            ],
            'json'  => $data,
        ];

        $profile = json_decode($this->client->post('me/messenger_profile', $options)->getBody()->getContents(), true);
        if (!empty($profile['data'])) {
            $response['getStarted'] = $profile['get_started'];
            $response['greeting'] = $profile['greeting'];
            $response['iceBreakers'] = $profile['ice_breakers'];
            $response['persistentMenu'] = $profile['persistent_menu'];
            $response['whitelistedDomains'] = $profile['whitelisted_domains'];
            $response['accountLinkingUrl'] = $profile['account_linking_url'];
            $response['homeUrl'] = $profile['home_url'];
        }

        return $response;
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
