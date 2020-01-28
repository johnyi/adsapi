<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\Campaign as FacebookCampaign;
use Illuminate\Http\Request;

class CampaignController extends MarketingController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'accountId' => 'required',
        ]);

        $params = [
            'before' => $request->input('before', null),
            'after'  => $request->input('after', null),
            'limit'  => $request->input('limit', 50),
        ];

        $campaigns = (new FacebookCampaign($this->appId, $this->appSecret, $this->accessToken))->get($request->input('accountId'), $params);

        return response()->json($campaigns);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',
            'objective' => 'required',
            'status'    => 'required',
            'accountId' => 'required',
        ]);

        $data = [
            'special_ad_category' => 'NONE',
            'name'                => $request->input('name'),
            'objective'           => $request->input('objective'),
            'status'              => $request->input('status'),
        ];

        $campaign = (new FacebookCampaign($this->appId, $this->appSecret, $this->accessToken))->create($request->input('accountId'), $data);

        return response()->json($campaign);
    }

    public function view(string $campaignId)
    {
        $campaign = (new FacebookCampaign($this->appId, $this->appSecret, $this->accessToken))->find($campaignId);

        return response()->json($campaign);
    }

    public function update(Request $request, string $campaignId)
    {
        $data = [];

        $name = $request->input('name');
        if (!empty($name)) {
            $data['name'] = $name;
        }

        $campaign = (new FacebookCampaign($this->appId, $this->appSecret, $this->accessToken))->update($campaignId, $data);

        return response()->json($campaign);
    }

    public function delete(string $campaignId)
    {
        $data = [];

        $campaign = (new FacebookCampaign($this->appId, $this->appSecret, $this->accessToken))->delete($campaignId, $data);

        return response()->json($campaign);
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
            'level'           => 'campaign',
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

        $insights = (new FacebookCampaign($this->appId, $this->appSecret, $this->accessToken))->insight($request->input('accountId'), $params);

        return response()->json($insights);
    }
}
