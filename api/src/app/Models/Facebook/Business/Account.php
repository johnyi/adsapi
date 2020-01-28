<?php

namespace App\Models\Facebook\Business;

use FacebookAds\Object\Business;

class Account extends Business
{
    public function get(string $managerId)
    {
        $response = [];

        $cursor = (new Business($managerId))->getOwnedAdAccounts([
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
                'accountId' => $row['account_id'],
                'name'      => $row['name'],
                'currency'  => $row['currency_code'],
                'timezone'  => $row['timezone_name'],
                'balance'   => $row['spend_cap'] / 100 - $row['amount_spent'] / 100,
                'spendCap'  => $row['spend_cap'] / 100,
            ];

            $cursor->next();
        }

        $cursor = (new Business($managerId))->getClientAdAccounts([
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
                'accountId' => $row['account_id'],
                'name'      => $row['name'],
                'currency'  => $row['currency_code'],
                'timezone'  => $row['timezone_name'],
                'balance'   => $row['spend_cap'] / 100 - $row['amount_spent'] / 100,
                'spendCap'  => $row['spend_cap'] / 100,
            ];

            $cursor->next();
        }

        return $response;
    }
}
