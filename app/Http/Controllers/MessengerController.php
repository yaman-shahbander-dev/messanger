<?php

namespace App\Http\Controllers;

use App\Services\Messenger\MessengerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessengerController extends Controller
{
    public function index(): View
    {
        return view('messenger.index');
    }

    public function search(Request $request)
    {
        $records = app(MessengerService::class)->search($request['query']);
        if ($records->total() < 1) $list = "<p class='text-center'> Nothing To Show! </p>";
        else $list = app(MessengerService::class)->renderSearchList($records);

        $lastPage = $records->lastPage();

        return response()->ok(
            data: ['data' => $list, 'last_page' => $lastPage]
        );
    }
}
