<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class MessengerController extends Controller
{
    protected $accessToken;

    protected $page;

    public function __construct(Request $request)
    {
        $page = Page::where('page_id', '=', $request->route()[2]['pageId'])->first();
        if (empty($page)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Page not exists',
            ], 400);
        }

        $this->page = $page;
    }
}
