<?php

namespace App\Http\Controllers;

use App\Actions\MessengerActions\GetUserByIdAction;
use App\Enums\OperationResultEnum;
use App\Helpers\OperationResult;
use App\Http\Requests\Auth\UpdateContactItemRequest;
use App\Http\Requests\DeleteMessageRequest;
use App\Http\Requests\FavoriteUserRequest;
use App\Http\Requests\FetchConversationMessagesRequest;
use App\Http\Requests\GetContactsRequest;
use App\Http\Requests\MakeMessagesSeenRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Services\Messenger\MessengerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessengerController extends Controller
{
    public function __construct(protected MessengerService $messengerService) {}
    public function index(): View
    {
        $favorites = $this->messengerService->getUserFavoriteUsers();
        return view('messenger.index', ['favorite_list' => $favorites]);
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

    public function fetchMessages(FetchConversationMessagesRequest $request)
    {
        $messages = $this->messengerService->fetchMessages($request->validated());

        if ($messages instanceof OperationResult) {
            return response()->ok(data: [
                'messages' => $messages->getMessage()
            ]);
        }

        $allMessages = null;
        foreach ($messages->reverse() as $message) {
            $allMessages .= $this->messengerService->renderMessageCard($message);
        }

        return response()->ok(data: [
            'last_page' => $messages->lastPage(),
            'messages' => $allMessages
        ]);
    }

    public function fetchContacts(GetContactsRequest $request)
    {
        $contacts = $this->messengerService->fetchContacts();

        if ($contacts instanceof OperationResult) {
            return response()->ok(data: [
                'contacts' => $contacts->getMessage()
            ]);
        }

        return response()->ok(data: [
            'contacts' => $contacts['contacts'],
            'last_page' => $contacts['last_page']
        ]);
    }

    public function updateContactItem(UpdateContactItemRequest $request)
    {
        $user = $this->messengerService->getUserById($request->validated());

        if ($user instanceof OperationResult) {
            return response()->failed($user->getMessage());
        }

        $contactItem = $this->messengerService->getContactItem($user);

        return response()->ok(data: [
            'contact_item' => $contactItem
        ]);
    }

    public function makeSeen(MakeMessagesSeenRequest $request)
    {
        $result = $this->messengerService->makeSeen($request->validated());

        if ($result instanceof OperationResult) {
            return response()->failed($result->getMessage());
        }

        return response()->ok(data: true);
    }

    public function favorite(FavoriteUserRequest $request)
    {
        $result = $this->messengerService->favorite($request->validated());

        if ($result instanceof OperationResult && $result->getStatus() === OperationResultEnum::FAILURE->value) {
            return response()->failed($result->getMessage());
        }

        return response()->ok(data: [
            'status' => $result->getMessage()
        ]);
    }

    public function fetchFavorite()
    {
        $favorites = $this->messengerService->fetchFavorite();

        if ($favorites instanceof OperationResult) {
            return response()->failed($favorites->getMessage());
        }

        return response()->ok(data: [
            'favorite_list' => $favorites
        ]);
    }

    public function deleteMessage(DeleteMessageRequest $request)
    {
        $result = $this->messengerService->deleteMessage($request->validated());

        if ($result instanceof OperationResult) {
            return response()->failed($result->getMessage());
        }

        return response()->ok();
    }
}
