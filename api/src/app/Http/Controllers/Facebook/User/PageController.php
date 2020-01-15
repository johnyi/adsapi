<?php

namespace App\Http\Controllers\Facebook\User;

use App\Http\Controllers\Facebook\UserController;
use Illuminate\Http\Request;

class PageController extends UserController
{
    public function index()
    {
        $response = $this->fb->get(sprintf('/%s/accounts', $this->user['user_id']), $this->user['access_token']);

        return response()->json([
            'data' => $response->getDecodedBody()['data'],
        ]);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required',
            'about'   => 'required',
            'picture' => 'required',
            'cover'   => 'required',
        ]);

        $data = [
            'name'        => $request->input('name'),
            'about'       => $request->input('about'),
            'picture'     => $request->input('picture'),
            'cover_photo' => ['url' => $request->input('cover')],
        ];

        $category = $request->input('category');
        if (!empty($category)) {
            $data['category_enum'] = $category;
        }

        $response = $this->fb->post(sprintf('/%s/accounts', $this->user['user_id']), $data, $this->user['access_token']);

        dd($response);
    }
}
