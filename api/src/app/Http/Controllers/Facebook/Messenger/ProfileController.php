<?php

namespace App\Http\Controllers\Facebook\Messenger;

use App\Http\Controllers\Facebook\MessengerController;
use App\Models\Facebook\Messenger\Profile;
use Illuminate\Http\Request;

class ProfileController extends MessengerController
{
    public function index()
    {
        $response = (new Profile($this->accessToken))->get();

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'profile' => 'required',
        ]);

        $response = (new Profile($this->accessToken))->update($request->input('profile'));

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'fields' => 'required',
        ]);

        $response = (new Profile($this->accessToken))->delete($request->input('fields'));

        return response()->json($response);
    }
}
