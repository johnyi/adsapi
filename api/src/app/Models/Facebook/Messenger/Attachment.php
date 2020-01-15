<?php

namespace App\Models\Facebook\Messenger;

use App\Models\Facebook\Messenger;

class Attachment extends Messenger
{
    public function uploadFromUrl($type, $url)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
            ],
            'json'  => [
                'message' => [
                    'attachment' => [
                        'type'    => $type,
                        'payload' => [
                            'is_reusable' => true,
                            'url'         => $url,
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->client->post('me/message_attachments', $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function uploadFromFile($type, $filePath)
    {
        $options = [
            'query'     => [
                'access_token' => $this->accessToken,
            ],
            'multipart' => [
                [
                    'name'     => 'message',
                    'contents' => json_encode([
                        'attachment' => [
                            'type'    => $type,
                            'payload' => [
                                'is_reusable' => true,
                            ],
                        ],
                    ]),
                ],
                [
                    'name'     => 'filedata',
                    'contents' => fopen($filePath, 'r'),
                ],
            ],
        ];

        $response = $this->client->post('me/message_attachments', $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}
