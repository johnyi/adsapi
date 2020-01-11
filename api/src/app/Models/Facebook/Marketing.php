<?php

namespace App\Models\Facebook;

use App\Models\Manager;
use FacebookAds\Api;
use FacebookAds\Cursor;
use FacebookAds\Logger\CurlLogger;

class Marketing
{
    public function __construct(Manager $manager)
    {
        $api = Api::init($manager['client_id'], $manager['client_secret'], $manager['access_token']);
        $api->setLogger(new CurlLogger());

        Cursor::setDefaultUseImplicitFetch(true);
    }
}
