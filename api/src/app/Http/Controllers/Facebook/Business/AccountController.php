<?php

namespace App\Http\Controllers\Facebook\Business;

use App\Http\Controllers\Facebook\BusinessController;
use Illuminate\Http\Request;

class AccountController extends BusinessController
{
    public function index(Request $request, string $managerId)
    {
        $limit = $request->input('limit', 50);

        $accounts = Account::where('manager_id', '=', $managerId)->paginate($limit);

        return response()->json([
            'total' => $accounts->total(),
            'items' => $accounts->items(),
        ]);
    }
}
