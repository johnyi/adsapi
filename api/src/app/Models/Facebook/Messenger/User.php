<?php

namespace App\Models\Facebook\Messenger;

use App\Models\Facebook\Messenger;
use Log;

class User extends Messenger
{
    protected static $fields = [
        'first_name',
        'last_name',
        'profile_pic',
        'locale',
        'timezone',
        'gender',
    ];

    public function profile($psId)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
                'fields'       => implode(',', self::$fields),
            ],
        ];

        Log::info('before request');

        $response = $this->client->request('GET', $psId, $options);

        Log::info(json_encode($response));

        return json_decode($response->getBody()->getContents(), true);
    }
}
