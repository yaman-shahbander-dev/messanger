<?php

namespace App\Actions\MessengerActions;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class GetUserFavoriteUsersAction
{
    public function __invoke()
    {
        return Favorite::query()
            ->with('user:id,name,avatar')
            ->where('user_id', Auth::user()->id)
            ->get();
    }
}
