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
            $response['getStarted'] = array_key_exists('get_started', $profile) ? $profile['get_started'] : null;
            $response['greeting'] = array_key_exists('greeting', $profile) ? $profile['greeting'] : null;
            $response['iceBreakers'] = array_key_exists('ice_breakers', $profile) ? $profile['ice_breakers'] : null;
            $response['persistentMenu'] = array_key_exists('persistent_menu', $profile) ? $profile['persistent_menu'] : null;
            $response['whitelistedDomains'] = array_key_exists('whitelisted_domains', $profile) ? $profile['whitelisted_domains'] : null;
            $response['accountLinkingUrl'] = array_key_exists('account_linking_url', $profile) ? $profile['account_linking_url'] : null;
            $response['homeUrl'] = array_key_exists('home_url', $profile) ? $profile['home_url'] : null;
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
            $response['getStarted'] = array_key_exists('get_started', $profile) ? $profile['get_started'] : null;
            $response['greeting'] = array_key_exists('greeting', $profile) ? $profile['greeting'] : null;
            $response['iceBreakers'] = array_key_exists('ice_breakers', $profile) ? $profile['ice_breakers'] : null;
            $response['persistentMenu'] = array_key_exists('persistent_menu', $profile) ? $profile['persistent_menu'] : null;
            $response['whitelistedDomains'] = array_key_exists('whitelisted_domains', $profile) ? $profile['whitelisted_domains'] : null;
            $response['accountLinkingUrl'] = array_key_exists('account_linking_url', $profile) ? $profile['account_linking_url'] : null;
            $response['homeUrl'] = array_key_exists('home_url', $profile) ? $profile['home_url'] : null;
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
