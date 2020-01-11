<?php

namespace App\Http\Controllers\Facebook\Marketing;

use App\Http\Controllers\Facebook\MarketingController;
use App\Models\Facebook\Marketing\Business;
use Illuminate\Http\Request;

class BusinessController extends MarketingController
{
    public function account()
    {
        $accounts = (new Business($this->manager))->getClientAdAccounts();

        return response()->json([
            'data' => $accounts,
        ]);

//        $limit = $request->input('limit', 50);
//
//        $accounts = Account::where('manager_id', $managerId)->paginate($limit);
//
//        return response()->json([
//            'total' => $accounts->total(),
//            'items' => $accounts->items(),
//        ]);
    }
}
