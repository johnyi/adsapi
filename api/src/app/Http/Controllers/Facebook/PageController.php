<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\FacebookController;
use App\Models\Page;
use App\Models\PageAudience;
use Illuminate\Http\Request;

class PageController extends FacebookController
{
    protected $accessToken;

    public function __construct(Request $request)
    {
        $this->validate($request, [
            'userId' => 'required',
        ]);

        parent::__construct();

        $page = Page::where('page_id', '=', $request->route()[2]['pageId'])->where('user_id', '=', $request->input('userId'))->first();
        if (empty($page)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Page not exists',
            ], 400);
        }

        $this->accessToken = $page['access_token'];
    }

    public function audience(Request $request, string $pageId)
    {
        $items = [];

        $audiences = PageAudience::where('page_id', '=', $pageId)->orderBy('updated_at', 'DESC')->paginate($request->input('limit', 50));
        foreach ($audiences->items() as $item) {
            $items[] = [
                'id'         => $item['id'],
                'pageId'     => $item['page_id'],
                'psId'       => $item['ps_id'],
                'name'       => $item['name'],
                'firstName'  => $item['first_name'],
                'lastName'   => $item['last_name'],
                'profilePic' => $item['profile_pic'],
                'locale'     => $item['locale'],
                'timezone'   => $item['timezone'],
                'gender'     => $item['gender'],
                'createdAt'  => $item['created_at'],
                'updatedAt'  => $item['updated_at'],
            ];
        }

        return response()->json([
            'total' => $audiences->total(),
            'items' => $items,
        ]);
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
