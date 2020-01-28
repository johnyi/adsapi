<?php

namespace App\Models\Facebook;

use App\Models\Manager;
use FacebookAds\Api;
use FacebookAds\Cursor;
use FacebookAds\Logger\CurlLogger;

class Marketing
{
    public function __construct(string $appId, string $appSecret, string $accessToken)
    {
        Api::init($appId, $appSecret, $accessToken)->setLogger(new CurlLogger());

        Cursor::setDefaultUseImplicitFetch(true);
    }
}
