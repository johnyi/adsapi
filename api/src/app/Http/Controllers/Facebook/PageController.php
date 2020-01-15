<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Facebook\Facebook;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $fb;

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

        $this->fb = new Facebook([
            'app_id'     => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
        ]);
    }

    public function subscribe()
    {
        $fields = [
            'messages',
            'messaging_postbacks',
            'message_reads',
            'messaging_referrals',
            'message_echoes',
        ];

        $response = $this->fb->post(sprintf('/%s/subscribed_apps?subscribed_fields=%s', $this->page['page_id'], implode(',', $fields)), [], $this->page['access_token']);

        return response()->json($response->getDecodedBody());
    }
}
