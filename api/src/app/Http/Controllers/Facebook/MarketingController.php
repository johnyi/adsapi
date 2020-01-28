<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    protected $appId;

    protected $appSecret;

    protected $accessToken;

    public function __construct(Request $request)
    {
        $managerId = $request->input('managerId');
        if (!empty($managerId)) {
            $manager = Manager::where('manager_id', '=', $managerId)->first();
            if (empty($manager)) {
                die('Manager not exists');
            }

            $this->appId = $manager['client_id'];
            $this->appSecret = $manager['client_secret'];
            $this->accessToken = $manager['access_token'];
        } else {
            $userId = $request->input('userId');
            if (!empty($userId)) {
                $user = User::where('user_id', '=', $userId)->first();
                if (empty($user)) {
                    die('User not exists');
                }

                $this->appId = env('FB_APP_ID');
                $this->appSecret = env('FB_APP_SECRET');
                $this->accessToken = $user['access_token'];
            }
        }

        if (empty($this->accessToken)) {
            die('access token not exists');
        }
    }
}
