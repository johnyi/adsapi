<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\Ad;
use App\Models\Facebook\Marketing\AdCreative;
use Illuminate\Http\Request;

class AdController extends MarketingController
{
    public function index(Request $request)
    {
        if (empty($request->input('accountId')) and empty($request->input('campaignId')) and empty($request->input('adSetId'))) {
            return response()->json([
                'code'    => -1,
                'message' => 'account id, campaign id和adset id不能同时为空',
            ], 400);
        }

        $data = [];

        $adSetId = $request->input('adSetId');
        if (!empty($adSetId)) {
            $data['adSetId'] = $adSetId;
        }

        $campaignId = $request->input('campaignId');
        if (!empty($campaignId)) {
            $data['campaignId'] = $campaignId;
        }

        $accountId = $request->input('accountId');
        if (!empty($accountId)) {
            $data['accountId'] = $accountId;
        }

        $params = [
            'effective_status' => $request->input('effectiveStatus', []),
            'before'           => $request->input('before', null),
            'after'            => $request->input('after', null),
            'limit'            => $request->input('limit', null),
        ];

        $ads = (new Ad($this->appId, $this->appSecret, $this->accessToken))->get($data, $params);

        return response()->json($ads);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'accountId' => 'required',
            'adSetId'   => 'required',
            'name'      => 'required',
            'status'    => 'required',
        ]);

        $accountId = $request->input('accountId');

        $adCreative = (new AdCreative($this->appId, $this->appSecret, $this->accessToken))->create($accountId, [
            'name'              => 'Test Ad Creative #1',
            'object_story_spec' => [
                'page_id'    => '101121868036411',
//                'link_data'  => [
//                    'call_to_action' => [
//                        'type'  => 'LEARN_MORE',
//                        'value' => ['app_destination' => 'MESSENGER'],
//                    ],
//                    'image_hash'     => 'ab5effaba5eca4d4760bc9717e50810e',
//                    'link'           => 'https://m.me/101121868036411',
//                    'message'        => 'Welcome message',
//                ],
//                'video_data' => [
//                    'call_to_action'       => [
//                        'type'  => 'LEARN_MORE',
//                        'value' => ['app_destination' => 'MESSENGER'],
//                    ],
//                    'link_description'     => 'Try it out',
//                    'image_url'            => 'https://scontent.xx.fbcdn.net/v/t15.5256-10/74717208_2482051165366194_3896844799986106368_n.jpg?_nc_cat=110&_nc_ohc=dB8YOAkelhQAX91pXcR&_nc_ht=scontent.xx&oh=b76ea640b16aff6d1d52fdc295ffbdce&oe=5ED3F42A',
//                    'page_welcome_message' => 'Welcome message in messenger',
//                    'video_id'             => '2482051032032874',
//                ],
            ],
        ]);

        dd($adCreative);

        $ad = (new Ad($this->appId, $this->appSecret, $this->accessToken))->create($accountId, [
            'adset_id' => $request->input('adSetId'),
            'name'     => $request->input('name'),
            'status'   => $request->input('status'),
            'creative' => ['creative_id' => $adCreative['id']],
        ]);

        return response()->json($ad);
    }

    public function view(string $adId)
    {
        $ad = (new Ad($this->appId, $this->appSecret, $this->accessToken))->find($adId);

        return response()->json($ad);
    }

    public function update(Request $request, string $adId)
    {
        $data = [];

        $name = $request->input('name');
        if (!empty($name)) {
            $data['name'] = $name;
        }

        $ad = (new Ad($this->appId, $this->appSecret, $this->accessToken))->update($adId, $data);

        return response()->json($ad);
    }

    public function delete(string $adId)
    {
        $data = [];

        $ad = (new Ad($this->appId, $this->appSecret, $this->accessToken))->delete($adId, $data);

        return response()->json($ad);
    }

    public function insight(Request $request)
    {
        $this->validate($request, [
            'accountId' => 'required',
            'start'     => 'required',
            'end'       => 'required',
        ]);

        $params = [
            'default_summary' => true,
            'level'           => 'ad',
            'sort'            => ['date_stop_ascending'],
            'time_increment'  => 1,
            'time_range'      => [
                'since' => $request->input('start'),
                'until' => $request->input('end'),
            ],
            'before'          => $request->input('before', null),
            'after'           => $request->input('after', null),
            'limit'           => $request->input('limit', null),
        ];

        $insights = (new Ad($this->appId, $this->appSecret, $this->accessToken))->insight($request->input('accountId'), $params);

        return response()->json($insights);
    }
}
