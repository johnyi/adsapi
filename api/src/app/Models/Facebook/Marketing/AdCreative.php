<?php

namespace App\Models\Facebook\Marketing;

use App\Models\Facebook\Marketing;
use FacebookAds\Object\AdAccount;

class AdCreative extends Marketing
{
    public function create(string $accountId, array $params)
    {
        $response = (new AdAccount('act_' . $accountId))->createAdCreative([], $params);

        return $response->exportAllData();
    }
}
