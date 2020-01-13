<?php

namespace App\Http\Controllers\Facebook\User;

use App\Http\Controllers\Facebook\UserController;
use Facebook\Facebook as FB;
use Illuminate\Http\Request;

class PageController extends UserController
{
    public function index()
    {
        $fb = new FB([
            'app_id'     => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
        ]);

        $response = $fb->get(sprintf('/%s/accounts', $this->user['user_id']), $this->user['access_token']);

        return response()->json([
            'data' => $response->getDecodedBody()['data'],
        ]);
    }
}
