<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $user = User::where('user_id', '=', $request->route()[2]['userId'])->first();
        if (empty($user)) {
            return response()->json([
                'code'    => -1,
                'message' => 'User not exists',
            ], 400);
        }

        $this->user = $user;
    }
}
