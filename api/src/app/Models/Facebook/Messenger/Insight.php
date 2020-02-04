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
        $response = [];

        $options = [
            'query' => [
                'access_token' => $this->accessToken,
                'metric'       => implode(',', self::$metrics),
                'since'        => $since,
                'until'        => $until,
            ],
        ];

        $insights = json_decode($this->client->get('me/insights', $options)->getBody()->getContents(), true);
        if (!empty($insights['data'])) {
            foreach ($insights['data'] as $insight) {
                $response[] = [
                    'totalMessagingConnections'   => $insight['page_messages_total_messaging_connections'],
                    'newConversationsUnique'      => $insight['page_messages_new_conversations_unique'],
                    'blockedConversationsUnique'  => $insight['page_messages_blocked_conversations_unique'],
                    'reportedConversationsUnique' => $insight['page_messages_reported_conversations_unique'],
                ];
            }
        }

        return $response;
    }
}
