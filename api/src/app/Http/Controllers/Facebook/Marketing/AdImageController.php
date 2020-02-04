<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\AdImage;
use Illuminate\Http\Request;

class AdImageController extends MarketingController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'accountId' => 'required',
        ]);

        $params = [
            'status' => $request->input('status', []),
            'before' => $request->input('before', null),
            'after'  => $request->input('after', null),
            'limit'  => $request->input('limit', null),
        ];

        $adImages = (new AdImage($this->appId, $this->appSecret, $this->accessToken))->get($request->input('accountId'), $params);

        return response()->json($adImages);
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

        if (!file_exists(storage_path('app/images/' . $accountId))) {
            mkdir(storage_path('app/images/' . $accountId));
        }

        $filePath = $file->storeAs('images/' . $accountId, $file->getClientOriginalName());

        $ext = $file->getClientOriginalExtension();
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $response = (new AdImage($this->appId, $this->appSecret, $this->accessToken))->create($accountId, ['filename' => storage_path('app/' . $filePath)]);
        } else {
            return response()->json([
                'code'    => -1,
                'message' => 'unknown file type',
            ], 400);
        }

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'accountId' => 'required',
            'hash'      => 'required',
        ]);

        $response = (new AdImage($this->appId, $this->appSecret, $this->accessToken))->delete($request->input('accountId'), ['hash' => $request->input('hash')]);

        return response()->json($response);
    }
}
