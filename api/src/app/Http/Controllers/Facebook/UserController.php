<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\FacebookController;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends FacebookController
{
    protected $accessToken;

    public function __construct(Request $request)
    {
        parent::__construct();

        $user = User::where('user_id', '=', $request->route()[2]['userId'])->first();
        if (empty($user)) {
            return response()->json([
                'code'    => -1,
                'message' => 'User not exists',
            ], 400);
        }

        $this->accessToken = $user['access_token'];
    }
}
