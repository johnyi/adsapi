<?php

namespace App\Http\Controllers\Facebook\Messenger;

use App\Http\Controllers\Facebook\MessengerController;
use App\Models\Facebook\Messenger\Message;
use Illuminate\Http\Request;

class MessageController extends MessengerController
{
    public function send(Request $request)
    {
        $this->validate($request, [
            'recipient' => 'required',
            'message'   => 'required',
        ]);

        $message = new Message($this->page['access_token']);
        $message->setMessageType('RESPONSE');
        $message->setRecipient($request->input('recipient'));
        $message->setMessage($request->input('message'));
        $response = $message->send();

        return response()->json($response);
    }
}
