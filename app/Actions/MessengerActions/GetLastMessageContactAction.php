<?php

namespace App\Actions\MessengerActions;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class GetLastMessageContactAction
{
    public function __invoke($user)
    {
        return Message::query()
            ->where('from_id', Auth::user()->id)
            ->where('to_id', $user->id)
            ->orWhere('from_id', $user->id)
            ->where('to_id', Auth::user()->id)
            ->latest()
            ->first();
    }
}
