<?php

namespace App\Actions\MessengerActions;

use App\Strategies\Contexts\TimeAgoContext;

class RenderMessageCardAction
{
    public function __invoke($message)
    {
        return view('messenger.components.message-card', [
            'message' => $message,
            'time' => app(TimeAgoContext::class)->getTimeAgo($message->created_at)
        ])->render();
    }
}
