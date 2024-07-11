<?php

namespace App\Actions\MessengerActions;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class SetFavoredRecordConditionsAction
{
    public function __invoke(array $data): Builder
    {
        $query = Favorite::query()
            ->where('user_id', Auth::user()->id)
            ->where('favorite_id', $data['id']);

        return $query;
    }
}
