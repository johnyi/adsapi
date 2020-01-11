<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use FacebookAds\Object\AdAccount;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    protected $account;

    public function __construct(Request $request)
    {
        $this->account = new AdAccount('act_' . $request->route()[2]['accountId']);
    }

    public function index()
    {
        $data = [];

        $cursor = $this->account->getCampaigns();

        while ($cursor->key() !== null) {
            $campaign = $cursor->current()->getData();

            $data[] = [
                'name' => $campaign['name'],
            ];

            $cursor->next();
        }

        return response()->json();
    }
}
