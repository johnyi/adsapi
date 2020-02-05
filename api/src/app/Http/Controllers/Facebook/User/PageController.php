<?php

namespace App\Http\Controllers\Facebook\User;

use App\Http\Controllers\Facebook\UserController;
use Illuminate\Http\Request;

class PageController extends UserController
{
    public function index($userId)
    {
        $response = [];

        $pages = $this->fb->get(sprintf('/%s/accounts', $userId), $this->accessToken)->getDecodedBody()['data'];
        if (!empty($pages)) {
            foreach ($pages as $page) {
                $response[] = [
                    'accessToken'  => $page['access_token'],
                    'category'     => $page['category'],
                    'categoryList' => $page['category_list'],
                    'name'         => $page['name'],
                    'id'           => $page['id'],
                    'tasks'        => $page['tasks'],
                ];
            }
        }

        return response()->json($response);
    }

    public function create(Request $request, $userId)
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

        $response = $this->fb->post(sprintf('/%s/accounts', $userId), $data, $this->accessToken);

        dd($response);
    }
}
