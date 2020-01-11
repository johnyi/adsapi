<?php

namespace App\Models\Facebook\Marketing;

use App\Models\Facebook\Marketing;
use App\Models\Manager;
use FacebookAds\Object\AdAccount;

class Account extends Marketing
{
    protected $account;

    public function __construct(Manager $manager, string $accountId)
    {
        parent::__construct($manager);

        $this->account = new AdAccount('act_' . $accountId);
    }

    public function get()
    {
        $response = $this->account->getSelf();

        return $response;
    }
}
