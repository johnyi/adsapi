<?php

namespace App\Http\Controllers\Facebook\Messenger;

use App\Http\Controllers\Facebook\MessengerController;
use App\Models\Facebook\Messenger\Insight;
use Illuminate\Http\Request;

class InsightController extends MessengerController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'start' => 'required',
            'end'   => 'required',
        ]);

        $start = date_create($request->input('start'))->getTimestamp();
        $end = date_create($request->input('end'))->getTimestamp();

        $response = (new Insight($this->accessToken))->get($start, $end);

        return response()->json($response);
    }
}
