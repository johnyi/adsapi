<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\AdSet as FacebookAdSet;
use FacebookAds\Object\Targeting;
use Illuminate\Http\Request;

class AdSetController extends MarketingController
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

        $adSets = (new FacebookAdSet($this->appId, $this->appSecret, $this->accessToken))->get($request->input('accountId'), $params);

        return response()->json($adSets);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name'             => 'required',
            'dailyBudget'      => 'required',
            'bidAmount'        => 'required',
            'optimizationGoal' => 'required',
            'billingEvent'     => 'required',
            'status'           => 'required',
            'campaignId'       => 'required',
            'accountId'        => 'required',
        ]);

        $targeting = new Targeting();

        $countries = $request->input('countries');
        if (!empty($countries)) {
            $targeting->setData([
                'geo_locations' => [
                    'countries' => $countries,
                ],
            ]);
        }

        $data = [
            'name'              => $request->input('name'),
            'daily_budget'      => $request->input('dailyBudget') * 100,
            'bid_amount'        => $request->input('bidAmount') * 100,
            'optimization_goal' => $request->input('optimizationGoal'),
            'billing_event'     => $request->input('billingEvent'),
            'targeting'         => $targeting,
            'status'            => $request->input('status'),
            'campaign_id'       => $request->input('campaignId'),
        ];

        $adSet = (new FacebookAdSet($this->appId, $this->appSecret, $this->accessToken))->create($request->input('accountId'), $data);

        return response()->json($adSet);
    }

    public function view(string $adSetId)
    {
        $adSet = (new FacebookAdSet($this->appId, $this->appSecret, $this->accessToken))->find($adSetId);

        return response()->json($adSet);
    }

    public function update(Request $request, string $adSetId)
    {
        $data = [];

        $name = $request->input('name');
        if (!empty($name)) {
            $data['name'] = $name;
        }

        $adSet = (new FacebookAdSet($this->appId, $this->appSecret, $this->accessToken))->update($adSetId, $data);

        return response()->json($adSet);
    }

    public function delete(string $adSetId)
    {
        $data = [];

        $adSet = (new FacebookAdSet($this->appId, $this->appSecret, $this->accessToken))->delete($adSetId, $data);

        return response()->json($adSet);
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
            'level'           => 'adset',
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

        $insights = (new FacebookAdSet($this->appId, $this->appSecret, $this->accessToken))->insight($request->input('accountId'), $params);

        return response()->json($insights);
    }
}
