<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    protected $accessToken;

    public function __construct(Request $request)
    {
        $manager = Manager::where('manager_id', '=', $request->route()[2]['managerId'])->first();
        if (empty($manager)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Manager not exists',
            ], 400);
        }

        $this->accessToken = $manager['access_token'];
    }
}
