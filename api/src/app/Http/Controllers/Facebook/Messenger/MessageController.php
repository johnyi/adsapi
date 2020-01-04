<?php

namespace App\Http\Controllers\Facebook\Messenger;

use App\Http\Controllers\Controller;
use App\Models\Facebook\Messenger\Message;
use App\Models\Page;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(Request $request, string $pageId)
    {
        $this->validate($request, [
            'recipient' => 'required',
            'message'   => 'required',
        ]);

        $page = Page::where('page_id', '=', $pageId)->first();
        if (empty($page)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Page not exists',
            ], 400);
        }

        $message = new Message($page['access_token']);
        $message->setMessageType('RESPONSE');
        $message->setRecipient($request->input('recipient'));
        $message->setMessage($request->input('message'));
        $response = $message->send();

        return response()->json($response);
    }
}
