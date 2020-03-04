<?php

namespace App\Http\Controllers\Facebook\Messenger;

use App\Http\Controllers\Facebook\MessengerController;
use App\Models\Facebook\Messenger\Message as FacebookMessage;
use App\Models\Message;
use App\Models\MessageAttachment;
use Illuminate\Http\Request;

class MessageController extends MessengerController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'userId' => 'required',
        ]);

        $items = [];

        $messages = Message::where('sender_id', '=', $request->input('userId'))->orWhere('recipient_id', '=', $request->input('userId'))->orderBy('timestamp', 'DESC')->paginate($request->input('limit', 50));
        foreach ($messages->items() as $item) {
            $attachment = MessageAttachment::where('message_id', '=', $item['message_id'])->get();

            $items[] = [
                'id'          => $item['id'],
                'type'        => $item['type'],
                'senderId'    => $item['sender_id'],
                'recipientId' => $item['recipient_id'],
                'messageId'   => $item['message_id'],
                'isRead'      => $item['is_read'],
                'text'        => $item['text'],
                'quickReply'  => $item['quick_reply'],
                'replyTo'     => $item['reply_to'],
                'createdAt'   => $item['created_at'],
                'attachments' => $attachment,
            ];
        }

        return response()->json([
            'total' => $messages->total(),
            'items' => $items,
        ]);
    }

    public function send(Request $request, string $pageId)
    {
        $this->validate($request, [
            'recipient' => 'required',
        ]);

        $text = $request->input('text');
        if (!empty($text)) {
            $response = (new FacebookMessage($this->accessToken))->sendText('RESPONSE', $request->input('recipient'), $text);
        } else {
            $type = $request->input('type');
            $url = $request->input('url');
            $attachmentId = $request->input('attachmentId');
            $file = $request->file('file');

            if (empty($type)) {
                return response()->json([
                    'code'    => -1,
                    'message' => '附件类型不能为空',
                ], 400);
            }

            if (empty($url) and empty($attachmentId) and empty($file)) {
                return response()->json([
                    'code'    => -1,
                    'message' => 'URL、attachment id和file不能同时为空',
                ], 400);
            }

            if (!empty($url)) {
                $response = (new FacebookMessage($this->accessToken))->sendUrl('RESPONSE', $request->input('recipient'), $type, $url);
            } elseif (!empty($attachmentId)) {
                $response = (new FacebookMessage($this->accessToken))->sendAttachment('RESPONSE', $request->input('recipient'), $type, $attachmentId);
            } else {
                if (!file_exists(storage_path('app/images/' . $pageId))) {
                    mkdir(storage_path('app/images/' . $pageId));
                }

                $filePath = $file->storeAs('images/' . $pageId, $file->getClientOriginalName());

                $response = (new FacebookMessage($this->accessToken))->sendFile($request->input('recipient'), $type, storage_path('app/' . $filePath));
            }
        }

        return response()->json($response);
    }
}
