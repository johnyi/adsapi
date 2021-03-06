<?php

namespace App\Models\Facebook\Messenger;

use App\Models\Facebook\Messenger;

class UserProfile extends Messenger
{
    protected static $fields = [
        'name',
        'first_name',
        'last_name',
        'profile_pic',
        'locale',
        'timezone',
        'gender',
    ];

    public function get($psId)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
                'fields'       => implode(',', self::$fields),
            ],
        ];

        $response = $this->client->get($psId, $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}
