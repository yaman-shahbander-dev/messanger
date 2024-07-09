<?php

namespace App\Actions\MessengerActions;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Enums\ChatSettingsEnum;

class GetUnseenCountAction
{
    public function __invoke($user)
    {
        return Message::query()
            ->where('from_id', $user->id)
            ->where('to_id', Auth::user()->id)
            ->where('seen', ChatSettingsEnum::NOTSEEN->value)
            ->count();
    }
}
