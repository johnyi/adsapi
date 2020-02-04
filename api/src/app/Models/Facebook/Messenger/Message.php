<?php

namespace App\Models\Facebook\Messenger;

use App\Models\Facebook\Messenger;

class Message extends Messenger
{
    public function sendText($messageType, $recipient, $text)
    {
        $options = [
            'query' => ['access_token' => $this->accessToken],
            'json'  => [
                'messaging_type' => $messageType,
                'recipient'      => ['id' => $recipient],
                'message'        => ['text' => $text],
            ],
        ];

        $response = json_decode($this->client->post('me/messages', $options)->getBody()->getContents(), true);

        return [
            'recipientId' => $response['recipient_id'],
            'messageId'   => $response['message_id'],
        ];
    }

    public function sendUrl($messageType, $recipient, $attachmentType, $url)
    {
        $options = [
            'query' => ['access_token' => $this->accessToken],
            'json'  => [
                'messaging_type' => $messageType,
                'recipient'      => ['id' => $recipient],
                'message'        => [
                    'attachment' => [
                        'type'    => $attachmentType,
                        'payload' => [
                            'url'         => $url,
                            'is_reusable' => true,
                        ],
                    ],
                ],
            ],
        ];

        $response = json_decode($this->client->post('me/messages', $options)->getBody()->getContents(), true);

        return [
            'recipientId' => $response['recipient_id'],
            'messageId'   => $response['message_id'],
        ];
    }

    public function sendFile($recipient, $attachmentType, $filePath)
    {
        $options = [
            'query'     => ['access_token' => $this->accessToken],
            'multipart' => [
                [
                    'name'     => 'recipient',
                    'contents' => json_encode(['id' => $recipient]),
                ],
                [
                    'name'     => 'message',
                    'contents' => json_encode([
                        'attachment' => [
                            'type'    => $attachmentType,
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

        $response = json_decode($this->client->post('me/messages', $options)->getBody()->getContents(), true);

        return [
            'recipientId' => $response['recipient_id'],
            'messageId'   => $response['message_id'],
        ];
    }

    public function sendAttachment($messageType, $recipient, $attachmentType, $attachmentId)
    {
        $options = [
            'query' => ['access_token' => $this->accessToken],
            'json'  => [
                'messaging_type' => $messageType,
                'recipient'      => ['id' => $recipient],
                'message'        => [
                    'attachment' => [
                        'type'    => $attachmentType,
                        'payload' => ['attachment_id' => $attachmentId],
                    ],
                ],
            ],
        ];

        $response = json_decode($this->client->post('me/messages', $options)->getBody()->getContents(), true);

        return [
            'recipientId' => $response['recipient_id'],
            'messageId'   => $response['message_id'],
        ];
    }
}
