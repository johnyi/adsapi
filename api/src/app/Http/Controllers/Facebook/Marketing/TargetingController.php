<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\TargetingSearch;
use Illuminate\Http\Request;

class TargetingController extends MarketingController
{
    public function search(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
        ]);

        $type = $request->input('type');
        $class = $request->input('class', null);
        $query = $request->input('query', null);

        $params = ['limit' => 1000];

        $locationTypes = $request->input('locationTypes');
        if (!empty($locationTypes)) {
            $params['location_types'] = $locationTypes;
        }

        $response = (new TargetingSearch($this->appId, $this->appSecret, $this->accessToken))->search($type, $class, $query, $params);

        return response()->json($response);
    }
}
