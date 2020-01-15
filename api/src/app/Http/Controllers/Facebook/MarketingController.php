<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    protected $accessToken;

    public function __construct(Request $request)
    {
        $managerId = $request->input('managerId');
        if (!empty($managerId)) {
            $manager = Manager::where('manager_id', '=', $managerId)->first();
            if (empty($manager)) {
                return response()->json([
                    'code'    => -1,
                    'message' => 'Manager not exists',
                ], 400);
            }

            $this->accessToken = $manager['access_token'];
        } else {
            $userId = $request->input('userId');
            if (!empty($userId)) {
                $user = User::where('user_id', '=', $userId)->first();
                if (empty($user)) {
                    return response()->json([
                        'code'    => -1,
                        'message' => 'User not exists',
                    ], 400);
                }

                $this->accessToken = $user['access_token'];
            }
        }

        if (empty($this->accessToken)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Access token not exists',
            ], 400);
        }
    }
}
