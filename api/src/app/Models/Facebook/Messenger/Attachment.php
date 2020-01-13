<?php

namespace App\Models\Facebook\Messenger;

use App\Models\Facebook\Messenger;

class Attachment extends Messenger
{
    protected $type;

    public function setType($type)
    {
        $this->type = $type;
    }

    public function uploadFromUrl($url)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
            ],
            'json'  => [
                'message' => [
                    'attachment' => [
                        'type'    => $this->type,
                        'payload' => [
                            'is_reusable' => true,
                            'url'         => $url,
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->client->post('me/message_attachments', $options);

        return $response->json();
    }

    public function uploadFromFile($filePath)
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
            ],
            'body'  => [
                'message'  => json_encode([
                    'attachment' => [
                        'type'    => $this->type,
                        'payload' => [
                            'is_reusable' => true,
                        ],
                    ],
                ]),
                'filedata' => fopen($filePath, 'r'),
            ],
        ];

        $response = $this->client->post('me/message_attachments', $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}
