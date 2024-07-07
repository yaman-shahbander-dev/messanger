<?php

namespace App\Services\Messenger;

use App\Actions\MessengerActions\RenderSearchUsersListAction;
use App\Actions\MessengerActions\SearchUsersAction;
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
}
