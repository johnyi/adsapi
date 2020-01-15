<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\Campaign as FacebookCampaign;
use Illuminate\Http\Request;

class CampaignController extends MarketingController
{
    public function index(Request $request)
    {
        $accountId = $request->input('accountId');
        if (empty($accountId)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Account id not exists',
            ], 400);
        }

        $campaigns = (new FacebookCampaign($this->accessToken))->get($accountId);

        return response()->json($campaigns);
    }

    public function create(Request $request)
    {
        $accountId = $request->input('accountId');
        if (empty($accountId)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Account id not exists',
            ], 400);
        }

        $campaign = (new FacebookCampaign($this->accessToken))->create($accountId);

        return response()->json($campaign);
    }
}
