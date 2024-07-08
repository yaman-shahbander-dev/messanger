<?php

namespace App\Services\Messenger;

use App\Actions\MessengerActions\FetchUserInfoByIdAction;
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
}
