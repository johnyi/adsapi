<?php

namespace App\Models\Facebook\Messenger;

use App\Models\Facebook\Messenger;

class Insight extends Messenger
{
    protected static $metrics = [
        'page_messages_total_messaging_connections',
        'page_messages_new_conversations_unique',
        'page_messages_blocked_conversations_unique',
        'page_messages_reported_conversations_unique',
    ];

    public function get($since, $until)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
                'metric'       => implode(',', self::$metrics),
            ],
        ];

        if (!empty($since)) {
            $options['query']['since'] = $since;
        }

        if (!empty($until)) {
            $options['query']['until'] = $until;
        }

        $response = $this->client->request('GET', 'insights', $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}
