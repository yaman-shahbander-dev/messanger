<?php

namespace App\Actions\MessengerActions;

use App\Enums\OperationResultEnum;
use App\Helpers\OperationResult;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class GetFavoriteListAction
{
    public function __invoke()
    {
        $list = app(GetUserFavoriteUsersAction::class)();

        if (count($list) < 1) return new OperationResult(OperationResultEnum::FAILURE->value, 'The user does not have any favored contacts!');

        return $this->renderFavoriteView($list);
    }

    private function renderFavoriteView(Collection $records)
    {
        $favorites = null;

        foreach ($records as $record) {
            $favorites .= app(RenderFavoriteItemAction::class)($record);
        }

        return $favorites;
    }
}
