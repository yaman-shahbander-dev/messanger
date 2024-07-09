<?php

namespace App\Actions\MessengerActions;

use App\Actions\MessengerActions\GetLastMessageContactAction;
use App\Actions\MessengerActions\GetUnseenCountAction;
class GetContactItemAction
{
    public function __invoke($user)
    {
        $message = app(GetLastMessageContactAction::class)($user);
        $unseenCounter = app(GetUnseenCountAction::class)($user);

        return view('messenger.components.contact-list-item', [
            'lastMessage' => $message,
            'unseenCounter' => $unseenCounter,
            'user' => $user
        ])->render();
    }
}
