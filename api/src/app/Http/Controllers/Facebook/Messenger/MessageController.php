<?php

namespace App\Http\Controllers\Facebook\Messenger;

use App\Http\Controllers\Facebook\MessengerController;
use App\Models\Facebook\Messenger\Message as FacebookMessage;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends MessengerController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'userId' => 'required',
        ]);

        $userId = $request->input('userId');
        $limit = $request->input('limit', 50);

        $messages = Message::where('sender_id', '=', $userId)
            ->orWhere('recipient_id', '=', $userId)
            ->orderBy('timestamp', 'ASC')
            ->paginate($limit);

        return response()->json([
            'total' => $messages->total(),
            'items' => $messages->items(),
        ]);
    }

    public function send(Request $request)
    {
        $this->validate($request, [
            'recipient' => 'required',
            'message'   => 'required',
        ]);

        $message = new FacebookMessage($this->page['access_token']);
        $message->setMessageType('RESPONSE');
        $message->setRecipient($request->input('recipient'));
        $message->setMessage($request->input('message'));
        $response = $message->send();

        return response()->json($response);
    }
}
