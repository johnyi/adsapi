<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Account;
use App\Models\Facebook\Marketing\Account as FacebookAccount;
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

    public function insight(Request $request, string $accountId)
    {
        $this->validate($request, [
            'start' => 'required',
            'end'   => 'required',
        ]);

        $params = [
            'default_summary' => true,
            'level'           => 'account',
            'sort'            => ['date_stop_ascending'],
            'time_increment'  => 1,
            'time_range'      => [
                'since' => $request->input('start'),
                'until' => $request->input('end'),
            ],
            'before'          => $request->input('before', null),
            'after'           => $request->input('after', null),
            'limit'           => $request->input('limit', null),
        ];

        $insights = (new FacebookAccount($this->appId, $this->appSecret, $this->accessToken))->insight($accountId, $params);

        return response()->json($insights);
    }
}
