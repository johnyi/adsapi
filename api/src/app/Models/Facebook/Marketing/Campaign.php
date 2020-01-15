<?php

namespace App\Models\Facebook\Marketing;

use App\Models\Facebook\Marketing;
use FacebookAds\Object\AdAccount;

class Campaign extends Marketing
{
    public function get(string $accountId)
    {
        $response = (new AdAccount('act_' . $accountId))->getCampaigns();
    }

    public function create(string $accountId)
    {
        $response = (new AdAccount('act_' . $accountId))->createCampaign();
    }
}
