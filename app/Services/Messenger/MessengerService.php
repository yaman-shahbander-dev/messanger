<?php

namespace App\Services\Messenger;

use App\Actions\MessengerActions\AddOrRemoveFavoriteAction;
use App\Actions\MessengerActions\DeleteMessageAction;
use App\Actions\MessengerActions\FetchContactsAction;
use App\Actions\MessengerActions\FetchMessagesAction;
use App\Actions\MessengerActions\FetchUserInfoByIdAction;
use App\Actions\MessengerActions\GetContactItemAction;
use App\Actions\MessengerActions\GetFavoriteListAction;
use App\Actions\MessengerActions\GetUserByIdAction;
use App\Actions\MessengerActions\GetUserFavoriteUsersAction;
use App\Actions\MessengerActions\MakeMessagesSeenAction;
use App\Actions\MessengerActions\RenderMessageCardAction;
use App\Actions\MessengerActions\RenderSearchUsersListAction;
use App\Actions\MessengerActions\SearchUsersAction;
use App\Actions\MessengerActions\StoreMessageAction;
use App\Http\Requests\StoreMessageRequest;
use Illuminate\Pagination\LengthAwarePaginator;

class MessengerService
{
    public function search(?string $search)
    {
        return app(SearchUsersAction::class)($search);
    }

    public function renderSearchList(LengthAwarePaginator $records)
    {
        return app(RenderSearchUsersListAction::class)($records);
    }

    public function fetchUserInfo(int $id)
    {
        return app(FetchUserInfoByIdAction::class)($id);
    }

    public function storeMessage(StoreMessageRequest $request)
    {
        return app(StoreMessageAction::class)($request);
    }

    public function renderMessageCard($message)
    {
        return app(RenderMessageCardAction::class)($message);
    }

    public function fetchMessages(array $data)
    {
        return app(FetchMessagesAction::class)($data);
    }

    public function fetchContacts()
    {
        return app(FetchContactsAction::class)();
    }

    public function getUserById(array $data)
    {
        return app(GetUserByIdAction::class)($data['userId']);
    }

    public function getContactItem($user)
    {
        return app(GetContactItemAction::class)($user);
    }

    public function makeSeen(array $data)
    {
        return app(MakeMessagesSeenAction::class)($data);
    }

    public function favorite(array $data)
    {
        return app(AddOrRemoveFavoriteAction::class)($data);
    }

    public function fetchFavorite()
    {
        return app(GetFavoriteListAction::class)();
    }

    public function getUserFavoriteUsers()
    {
        return app(GetUserFavoriteUsersAction::class)();
    }

    public function deleteMessage(array $data)
    {
        return app(DeleteMessageAction::class)($data);
    }
}
