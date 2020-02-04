<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\AdVideo;
use Illuminate\Http\Request;

class AdVideoController extends MarketingController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'accountId' => 'required',
        ]);

        $params = [
            'before' => $request->input('before', null),
            'after'  => $request->input('after', null),
            'limit'  => $request->input('limit', null),
        ];

        $adVideos = (new AdVideo($this->appId, $this->appSecret, $this->accessToken))->get($request->input('accountId'), $params);

        return response()->json($adVideos);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'accountId' => 'required',
        ]);

        $file = $request->file('file');
        if (empty($file)) {
            return response()->json([
                'code'    => -1,
                'message' => 'file not exist',
            ], 400);
        }

        $accountId = $request->input('accountId');

        if (!file_exists(storage_path('app/videos/' . $accountId))) {
            mkdir(storage_path('app/videos/' . $accountId));
        }

        $filePath = $file->storeAs('videos/' . $accountId, $file->getClientOriginalName());

        $params = [
            'name'   => $file->getClientOriginalName(),
            'source' => storage_path('app/' . $filePath),
        ];

        $response = (new AdVideo($this->appId, $this->appSecret, $this->accessToken))->create($accountId, $params);

        return response()->json($response);
    }

    public function delete(Request $request, string $adVideoId)
    {
        $this->validate($request, [
            'accountId' => 'required',
        ]);

        $response = (new AdVideo($this->appId, $this->appSecret, $this->accessToken))->delete($request->input('accountId'), ['video_id' => $adVideoId]);

        return response()->json($response);
    }
}
