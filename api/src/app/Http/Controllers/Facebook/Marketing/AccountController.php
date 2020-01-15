<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\Account;
use Illuminate\Http\Request;

class AccountController extends MarketingController
{
    public function index()
    {
    }

    public function view(Request $request, string $accountId)
    {
        $account = (new Account($this->manager, $accountId))->get();

        return response()->json([
            'data' => $account,
        ]);
    }
}
