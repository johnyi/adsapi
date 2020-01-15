<?php

namespace App\Models\Facebook;

use FacebookAds\Api;
use FacebookAds\Cursor;
use FacebookAds\Logger\CurlLogger;

class Business
{
    public function __construct(string $accessToken)
    {
        $api = Api::init(env('FB_APP_ID'), env('FB_APP_SECRET'), $accessToken);
        $api->setLogger(new CurlLogger());

        Cursor::setDefaultUseImplicitFetch(true);
    }
}
