<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\Campaign;
use Illuminate\Http\Request;

class CampaignController extends MarketingController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'accountId' => 'required',
        ]);

        $params = [
            'effective_status' => $request->input('effectiveStatus', []),
            'before'           => $request->input('before', null),
            'after'            => $request->input('after', null),
            'limit'            => $request->input('limit', null),
        ];

        $campaigns = (new Campaign($this->appId, $this->appSecret, $this->accessToken))->get($request->input('accountId'), $params);

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

        $params = [
            'special_ad_category' => 'NONE',
            'name'                => $request->input('name'),
            'objective'           => $request->input('objective'),
            'status'              => $request->input('status'),
        ];

        $campaign = (new Campaign($this->appId, $this->appSecret, $this->accessToken))->create($request->input('accountId'), $params);

        return response()->json($campaign);
    }

    public function view(string $campaignId)
    {
        $campaign = (new Campaign($this->appId, $this->appSecret, $this->accessToken))->find($campaignId);

        return response()->json($campaign);
    }

    public function update(Request $request, string $campaignId)
    {
        $params = [];

        $name = $request->input('name');
        if (!empty($name)) {
            $params['name'] = $name;
        }

        $status = $request->input('status');
        if (!empty($status)) {
            $params['status'] = $status;
        }

        $campaign = (new Campaign($this->appId, $this->appSecret, $this->accessToken))->update($campaignId, $params);

        return response()->json($campaign);
    }

    public function delete(string $campaignId)
    {
        $campaign = (new Campaign($this->appId, $this->appSecret, $this->accessToken))->delete($campaignId);

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

        $insights = (new Campaign($this->appId, $this->appSecret, $this->accessToken))->insight($request->input('accountId'), $params);

        return response()->json($insights);
    }
}
