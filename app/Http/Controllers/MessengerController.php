<?php

namespace App\Http\Controllers;

use App\Helpers\OperationResult;
use App\Http\Requests\StoreMessageRequest;
use App\Services\Messenger\MessengerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessengerController extends Controller
{
    public function __construct(protected MessengerService $messengerService) {}
    public function index(): View
    {
        return view('messenger.index');
    }

    public function search(Request $request)
    {
        $records = $this->messengerService->search($request['query']);
        if ($records->total() < 1) $list = "<p class='text-center'> Nothing To Show! </p>";
        else $list = $this->messengerService->renderSearchList($records);

        $lastPage = $records->lastPage();

        return response()->ok(
            data: ['data' => $list, 'last_page' => $lastPage]
        );
    }

    public function fetchUserInfo(Request $request)
    {
        $user = $this->messengerService->fetchUserInfo($request['id']);

        if ($user instanceof OperationResult) {
            return response()->failed($user->getMessage());
        }

        return response()->ok(data: $user);
    }

    public function sendMessage(StoreMessageRequest $request)
    {
        $message = $this->messengerService->storeMessage($request);

        if ($message instanceof OperationResult) {
            return response()->failed($message->getMessage());
        }

        $view = $this->messengerService->renderMessageCard($message);

        return response()->ok(data: [
            'message' => $view,
            'tempID' => $request->temporaryMsgId
        ]);
    }
}
