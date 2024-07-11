<?php

namespace App\Actions\MessengerActions;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class GetUserSentAttachmentsConversationAction
{
    public function __invoke(int $id)
    {
        return Message::query()
            ->where('from_id', Auth::user()->id)
            ->where('to_id', $id)
            ->whereNotNull('attachment')
            ->orWhere('from_id',  $id)
            ->where('to_id', Auth::user()->id)
            ->whereNotNull('attachment')
            ->latest()
            ->get() ?? null;
    }
}
