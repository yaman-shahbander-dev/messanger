<?php

namespace App\Actions\MessengerActions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SearchUsersAction
{
    public function __invoke(?string $search)
    {
        return User::query()
            ->where('id', '!=', Auth::user()->id)
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('user_name', 'LIKE', "%{$search}%")
            ->paginate(10);
    }
}
