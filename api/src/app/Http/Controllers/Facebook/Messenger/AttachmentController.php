<?php

namespace App\Http\Controllers\Facebook\Messenger;

use App\Http\Controllers\Controller;
use App\Models\Facebook\Messenger\Attachment as FacebookAttachment;
use App\Models\Attachment;
use App\Models\Page;
use Illuminate\Http\Request;
use Log;

class AttachmentController extends Controller
{
    public function upload(Request $request, string $pageId)
    {
        $this->validate($request, [
            'type' => 'required',
        ]);

        $page = Page::where('page_id', '=', $pageId)->first();
        if (empty($page)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Page不存在',
            ], 400);
        }

        $response = [];

        $type = $request->input('type');
        $url = $request->input('url');
        $file = $request->file('file');

        $attachment = new FacebookAttachment($page['access_token']);
        $attachment->setType($type);

        if (!empty($url)) {
            $response = $attachment->uploadFromUrl($url);
        } elseif (!empty($file)) {
            if (!file_exists(storage_path('app/images/' . $pageId))) {
                mkdir(storage_path('app/images/' . $pageId));
            }

            $filePath = $file->storeAs('images/' . $pageId, $file->getClientOriginalName());

            $response = $attachment->uploadFromFile(storage_path('app/' . $filePath));
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
