<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\Ad as FacebookAd;
use App\Models\Facebook\Marketing\AdSet as FacebookAdSet;
use Illuminate\Http\Request;

class AdController extends MarketingController
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

        $ads = (new FacebookAd($this->appId, $this->appSecret, $this->accessToken))->get($request->input('accountId'), $params);

        return response()->json($ads);
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

        $adCreativeId = '';

        $data = [
            'name'     => $request->input('name'),
            'creative' => ['creative_id' => $adCreativeId],
            'status'   => $request->input('status'),
            'adset_id' => $request->input('adSetId'),
        ];

//        $params = array(
//            'creative' => array('creative_id' => '<adCreativeID>'),
//            'status' => 'PAUSED',
//        );

        $ad = (new FacebookAd($this->appId, $this->appSecret, $this->accessToken))->create($request->input('accountId'), $data);

        return response()->json($ad);
    }

    public function view(string $adId)
    {
        $ad = (new FacebookAd($this->appId, $this->appSecret, $this->accessToken))->find($adId);

        return response()->json($ad);
    }

    public function update(Request $request, string $adId)
    {
        $data = [];

        $name = $request->input('name');
        if (!empty($name)) {
            $data['name'] = $name;
        }

        $ad = (new FacebookAd($this->appId, $this->appSecret, $this->accessToken))->update($adId, $data);

        return response()->json($ad);
    }

    public function delete(string $adId)
    {
        $data = [];

        $ad = (new FacebookAd($this->appId, $this->appSecret, $this->accessToken))->delete($adId, $data);

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

        $insights = (new FacebookAd($this->appId, $this->appSecret, $this->accessToken))->insight($request->input('accountId'), $params);

        return response()->json($insights);
    }
}
