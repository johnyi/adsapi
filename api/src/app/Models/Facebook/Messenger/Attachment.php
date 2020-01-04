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

        $response = $this->client->request('POST', 'me/message_attachments', $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function uploadFromFile($filePath)
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
                            'type'    => $this->type,
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

        $response = $this->client->request('POST', 'me/message_attachments', $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}
