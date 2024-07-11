<?php

namespace App\Actions\MessengerActions;

class RenderSentAttachmentsConversationAction
{
    public function __invoke($record)
    {
        return view('messenger.components.gallery-item', ['photo' => $record])->render();
    }
}
