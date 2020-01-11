<?php

namespace App\Models\Facebook\Marketing;

use App\Models\Facebook\Marketing;
use App\Models\Manager;
use FacebookAds\Object\Business as FacebookBusiness;

class Business extends Marketing
{
    protected $business;

    public function __construct(Manager $manager)
    {
        parent::__construct($manager);

        $this->business = new FacebookBusiness($manager['manager_id']);
    }

    public function getClientAdAccounts()
    {
        $response = [];

        $cursor = $this->business->getClientAdAccounts([
            'account_id',
            'name',
            'currency',
            'timezone_name',
            'spend_cap',
            'amount_spent',
        ]);

        while ($cursor->key() !== null) {
            $row = $cursor->current()->getData();

            $response[] = [
                'accountId'   => $row['account_id'],
                'name'        => $row['name'],
                'currency'    => $row['currency'],
                'timezone'    => $row['timezone_name'],
                'spendCap'    => $row['spend_cap'],
                'amountSpent' => $row['amount_spent'],
            ];

            $cursor->next();
        }

        return $response;
    }
}
