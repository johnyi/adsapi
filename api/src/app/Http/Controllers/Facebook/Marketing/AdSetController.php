<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\AdSet;
use FacebookAds\Object\Targeting;
use Illuminate\Http\Request;

class AdSetController extends MarketingController
{
    public function index(Request $request)
    {
        if (empty($request->input('accountId')) and empty($request->input('campaignId'))) {
            return response()->json([
                'code'    => -1,
                'message' => 'account id和campaign id不能同时为空',
            ], 400);
        }

        $data = [];

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

        $adSets = (new AdSet($this->appId, $this->appSecret, $this->accessToken))->get($data, $params);

        return response()->json($adSets);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'accountId'        => 'required',
            'campaignId'       => 'required',
            'name'             => 'required',
            'bidAmount'        => 'required',
            'billingEvent'     => 'required',
            'dailyBudget'      => 'required',
            'optimizationGoal' => 'required',
            'status'           => 'required',
        ]);

        $params = [
            'campaign_id'       => $request->input('campaignId'),
            'name'              => $request->input('name'),
            'bid_amount'        => $request->input('bidAmount') * 100,
            'billing_event'     => $request->input('billingEvent'),
            'daily_budget'      => $request->input('dailyBudget') * 100,
            'optimization_goal' => $request->input('optimizationGoal'),
            'status'            => $request->input('status'),
        ];

        $targetingData = [];

        $targetingData['age_min'] = $request->input('ageMin', 18);
        $targetingData['age_max'] = $request->input('ageMax', 65);

        $geoLocations = [];

        $countries = $request->input('countries');
        if (!empty($countries)) {
            $geoLocations['countries'] = $countries;
        }

        $locationTypes = $request->input('locationTypes');
        if (!empty($locationTypes)) {
            $geoLocations['location_types'] = $locationTypes;
        }

        if (!empty($geoLocations)) {
            $targetingData['geo_locations'] = $geoLocations;
        }

        $genders = $request->input('genders');
        if (!empty($genders)) {
            $targetingData['genders'] = $genders;
        }

        $locales = $request->input('locales');
        if (!empty($locales)) {
            $targetingData['locales'] = $locales;
        }

        $behaviors = $request->input('behaviors');
        if (!empty($behaviors)) {
            $targetingData['behaviors'] = $behaviors;
        }

        $interests = $request->input('interests');
        if (!empty($interests)) {
            $targetingData['interests'] = $interests;
        }

        $lifeEvents = $request->input('lifeEvents');
        if (!empty($lifeEvents)) {
            $targetingData['life_events'] = $lifeEvents;
        }

        $devicePlatforms = $request->input('devicePlatforms');
        if (!empty($devicePlatforms)) {
            $targetingData['device_platforms'] = $devicePlatforms;
        }

        $publisherPlatforms = $request->input('publisherPlatforms');
        if (!empty($publisherPlatforms)) {
            $targetingData['publisher_platforms'] = $publisherPlatforms;
        }

        $facebookPositions = $request->input('facebookPositions');
        if (!empty($facebookPositions)) {
            $targetingData['facebook_positions'] = $facebookPositions;
        }

        $instagramPositions = $request->input('instagramPositions');
        if (!empty($instagramPositions)) {
            $targetingData['instagram_positions'] = $instagramPositions;
        }

        $audienceNetworkPositions = $request->input('audienceNetworkPositions');
        if (!empty($audienceNetworkPositions)) {
            $targetingData['audience_network_positions'] = $audienceNetworkPositions;
        }

        $messengerPositions = $request->input('messengerPositions');
        if (!empty($messengerPositions)) {
            $targetingData['messenger_positions'] = $messengerPositions;
        }

        if (!empty($targetingData)) {
            $params['targeting'] = (new Targeting())->setData($targetingData);
        }

        $adSet = (new AdSet($this->appId, $this->appSecret, $this->accessToken))->create($request->input('accountId'), $params);

        return response()->json($adSet);
    }

    public function view(string $adSetId)
    {
        $adSet = (new AdSet($this->appId, $this->appSecret, $this->accessToken))->find($adSetId);

        return response()->json($adSet);
    }

    public function update(Request $request, string $adSetId)
    {
        $params = [];

        $name = $request->input('name');
        if (!empty($name)) {
            $params['name'] = $name;
        }

        $bidAmount = $request->input('bidAmount');
        if (!empty($bidAmount)) {
            $params['bid_amount'] = $bidAmount * 100;
        }

        $billingEvent = $request->input('billingEvent');
        if (!empty($billingEvent)) {
            $params['billing_event'] = $billingEvent;
        }

        $dailyBudget = $request->input('dailyBudget');
        if (!empty($dailyBudget)) {
            $params['daily_budget'] = $dailyBudget * 100;
        }

        $optimizationGoal = $request->input('optimizationGoal');
        if (!empty($optimizationGoal)) {
            $params['optimization_goal'] = $optimizationGoal;
        }

        $status = $request->input('status');
        if (!empty($status)) {
            $params['status'] = $status;
        }

        $targetingData = [];

        $targetingData['age_min'] = $request->input('ageMin', 18);
        $targetingData['age_max'] = $request->input('ageMax', 65);

        $geoLocations = [];

        $countries = $request->input('countries');
        if (!empty($countries)) {
            $geoLocations['countries'] = $countries;
        }

        $locationTypes = $request->input('locationTypes');
        if (!empty($locationTypes)) {
            $geoLocations['location_types'] = $locationTypes;
        }

        if (!empty($geoLocations)) {
            $targetingData['geo_locations'] = $geoLocations;
        }

        $genders = $request->input('genders');
        if (!empty($genders)) {
            $targetingData['genders'] = $genders;
        }

        $locales = $request->input('locales');
        if (!empty($locales)) {
            $targetingData['locales'] = $locales;
        }

        $behaviors = $request->input('behaviors');
        if (!empty($behaviors)) {
            $targetingData['behaviors'] = $behaviors;
        }

        $interests = $request->input('interests');
        if (!empty($interests)) {
            $targetingData['interests'] = $interests;
        }

        $lifeEvents = $request->input('lifeEvents');
        if (!empty($lifeEvents)) {
            $targetingData['life_events'] = $lifeEvents;
        }

        $devicePlatforms = $request->input('devicePlatforms');
        if (!empty($devicePlatforms)) {
            $targetingData['device_platforms'] = $devicePlatforms;
        }

        $publisherPlatforms = $request->input('publisherPlatforms');
        if (!empty($publisherPlatforms)) {
            $targetingData['publisher_platforms'] = $publisherPlatforms;
        }

        $facebookPositions = $request->input('facebookPositions');
        if (!empty($facebookPositions)) {
            $targetingData['facebook_positions'] = $facebookPositions;
        }

        $instagramPositions = $request->input('instagramPositions');
        if (!empty($instagramPositions)) {
            $targetingData['instagram_positions'] = $instagramPositions;
        }

        $audienceNetworkPositions = $request->input('audienceNetworkPositions');
        if (!empty($audienceNetworkPositions)) {
            $targetingData['audience_network_positions'] = $audienceNetworkPositions;
        }

        $messengerPositions = $request->input('messengerPositions');
        if (!empty($messengerPositions)) {
            $targetingData['messenger_positions'] = $messengerPositions;
        }

        if (!empty($targetingData)) {
            $params['targeting'] = (new Targeting())->setData($targetingData);
        }

        $adSet = (new AdSet($this->appId, $this->appSecret, $this->accessToken))->update($adSetId, $params);

        return response()->json($adSet);
    }

    public function delete(string $adSetId)
    {
        $adSet = (new AdSet($this->appId, $this->appSecret, $this->accessToken))->delete($adSetId);

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

        $insights = (new AdSet($this->appId, $this->appSecret, $this->accessToken))->insight($request->input('accountId'), $params);

        return response()->json($insights);
    }
}
