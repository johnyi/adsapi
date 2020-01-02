<?php

namespace App\Models\Facebook\Messenger;

use GuzzleHttp\Client;

class Message
{
    protected $url;

    protected $client;

    protected $messageType;

    protected $recipient;

    protected $message;

    protected $senderAction;

    protected $notificationType;

    protected $tag;

    public function __construct($accessToken)
    {
        $this->url = sprintf('https://graph.facebook.com/v5.0/me/messages?access_token=%s', $accessToken);
        $this->client = new Client();
    }

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
        $data = [
            'messaging_type' => $this->messageType,
            'recipient'      => ['id' => $this->recipient],
            'message'        => $this->message,
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $data]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
