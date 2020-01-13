<?php

namespace App\Http\Controllers;

use App\Jobs\Facebook\User\FacebookAllUserPageSync;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->input('name');
        if (empty($name)) {
            return response()->json([
                'message' => 'job not exists',
            ]);
        }

        $jobs = [
            'fb-all-user-page-sync' => FacebookAllUserPageSync::class,
        ];

        $this->dispatch(new $jobs[$name]($request->all() ?: []));

        return response()->json([
            'message' => 'ok',
        ]);
    }
}
