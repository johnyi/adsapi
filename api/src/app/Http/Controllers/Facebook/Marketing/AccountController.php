<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends MarketingController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'userId' => 'required',
        ]);

        $accounts = Account::where('user_id', '=', $request->input('userId'))->paginate($request->input('limit', 50));

        return response()->json([
            'total' => $accounts->total(),
            'items' => $accounts->items(),
        ]);
    }

    public function view(Request $request, string $accountId)
    {
        $this->validate($request, [
            'userId' => 'required',
        ]);

        $account = Account::where('account_id', '=', $accountId)->where('user_id', '=', $userId = $request->input('userId'))->first();

        return response()->json($account);
    }
}
