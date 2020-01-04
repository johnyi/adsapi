<?php

namespace App\Http\Controllers\Facebook\Messenger;

use App\Http\Controllers\Controller;
use App\Models\Facebook\Messenger\Insight;
use App\Models\Page;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function index(Request $request, string $pageId)
    {
        $this->validate($request, [
            'start' => 'required',
            'end'   => 'required',
        ]);

        $page = Page::where('page_id', '=', $pageId)->first();
        if (empty($page)) {
            return response()->json([
                'code'    => -1,
                'message' => 'Page not exists',
            ], 400);
        }

        $start = date_create($request->input('start'))->getTimestamp();
        $end = date_create($request->input('end'))->getTimestamp();

        $insight = new Insight($page['access_token']);
        $response = $insight->get($start, $end);

        return response()->json($response);
    }
}
