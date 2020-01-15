<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\FacebookController;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends FacebookController
{
    protected $accessToken;

    public function __construct(Request $request)
    {
        parent::__construct();

        $page = Page::where('page_id', '=', $request->route()[2]['pageId'])->first();
        if (empty($page)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Page not exists',
            ], 400);
        }

        $this->accessToken = $page['access_token'];
    }

    public function subscribe(string $pageId)
    {
        $fields = [
            'messages',
            'messaging_postbacks',
            'message_reads',
            'messaging_referrals',
            'message_echoes',
        ];

        $response = $this->fb->post(sprintf('/%s/subscribed_apps?subscribed_fields=%s', $pageId, implode(',', $fields)), [], $this->accessToken);

        return response()->json($response->getDecodedBody());
    }
}
