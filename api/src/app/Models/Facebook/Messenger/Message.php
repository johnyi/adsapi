<?php

namespace App\Models\Facebook\Messenger;

use App\Models\Facebook\Messenger;

class Message extends Messenger
{
    protected $messageType;

    protected $recipient;

    protected $message;

    protected $senderAction;

    protected $notificationType;

    protected $tag;

    public function setMessageType($messageType)
    {
        $this->messageType = $messageType;
    }

    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function send()
    {
        $options = [
            'query' => [
                'access_token' => $this->accessToken,
            ],
            'json'  => [
                'messaging_type' => $this->messageType,
                'recipient'      => ['id' => $this->recipient],
                'message'        => $this->message,
            ],
        ];

        $response = $this->client->request('POST', 'me/messages', $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}
