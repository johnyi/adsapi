<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class MessengerController extends Controller
{
    protected $accessToken;

    public function __construct(Request $request)
    {
        $this->validate($request, [
            'userId' => 'required',
        ]);

        $page = Page::where('page_id', '=', $request->route()[2]['pageId'])->where('user_id', '=', $request->input('userId'))->first();
        if (empty($page)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Page not exists',
            ], 400);
        }

        $this->accessToken = $page['access_token'];
    }
}
