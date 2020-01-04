<?php

namespace App\Http\Controllers\Facebook\Messenger;

use App\Http\Controllers\Facebook\MessengerController;
use App\Models\Facebook\Messenger\Profile;
use Illuminate\Http\Request;

class ProfileController extends MessengerController
{
    public function index()
    {
        $profile = new Profile($this->page['access_token']);
        $response = $profile->get();

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'profile' => 'required',
        ]);

        $profile = new Profile($this->page['access_token']);
        $response = $profile->update($request->input('profile'));

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'fields' => 'required',
        ]);

        $profile = new Profile($this->page['access_token']);
        $response = $profile->delete($request->input('fields'));

        return response()->json($response);
    }
}
