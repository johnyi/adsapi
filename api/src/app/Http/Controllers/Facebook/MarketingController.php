<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    protected $manager;

    public function __construct(Request $request)
    {
        $manager = Manager::where('manager_id', '=', $request->route()[2]['managerId'])->first();
        if (empty($manager)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Manager not exists',
            ], 400);
        }

        $this->manager = $manager;
    }
}
