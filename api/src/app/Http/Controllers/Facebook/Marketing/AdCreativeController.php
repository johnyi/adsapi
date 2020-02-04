<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\AdCreative;

class AdCreativeController extends MarketingController
{
    public function view(string $adCreativeId)
    {
        $adCreative = (new AdCreative($this->appId, $this->appSecret, $this->accessToken))->find($adCreativeId);

        return response()->json($adCreative);
    }
}
