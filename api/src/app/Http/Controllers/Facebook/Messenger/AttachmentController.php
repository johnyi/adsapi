<?php

namespace App\Http\Controllers\Facebook\Messenger;

use App\Http\Controllers\Facebook\MessengerController;
use App\Models\Attachment;
use App\Models\Facebook\Messenger\Attachment as FacebookAttachment;
use Illuminate\Http\Request;

class AttachmentController extends MessengerController
{
    public function index(Request $request, string $pageId)
    {
        $limit = $request->input('limit', 50);

        $attachments = Attachment::where('page_id', '=', $pageId)->orderBy('created_at', 'DESC')->paginate($limit);

        return response()->json([
            'total' => $attachments->total(),
            'items' => $attachments->items(),
        ]);
    }

    public function upload(Request $request, string $pageId)
    {
        $this->validate($request, [
            'type' => 'required',
        ]);

        $type = $request->input('type');
        $url = $request->input('url');
        $file = $request->file('file');

        if (!empty($url)) {
            $response = (new FacebookAttachment($this->accessToken))->uploadFromUrl($type, $url);
        } elseif (!empty($file)) {
            if (!file_exists(storage_path('app/images/' . $pageId))) {
                mkdir(storage_path('app/images/' . $pageId));
            }

            $filePath = $file->storeAs('images/' . $pageId, $file->getClientOriginalName());

            $response = (new FacebookAttachment($this->accessToken))->uploadFromFile($type, storage_path('app/' . $filePath));
        } else {
            return response()->json([
                'code'    => -1,
                'message' => 'attachment not exist',
            ], 400);
        }

        $attachment = new Attachment();
        $attachment['type'] = $type;
        $attachment['name'] = $file->getClientOriginalName();
        $attachment['page_id'] = $pageId;
        $attachment['attachment_id'] = $response['attachment_id'];
        $attachment['created_at'] = date_create()->format('Y-m-d H:i:s');
        $attachment->save();

        return response()->json($response);
    }
}
