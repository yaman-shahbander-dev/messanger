<?php

namespace App\Actions\MessengerActions;

use App\Enums\OperationResultEnum;
use App\Helpers\OperationResult;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class FetchMessagesAction
{
    public function __invoke(array $data)
    {
        $messages = Message::query()
            ->where('from_id', Auth::user()->id)
            ->where('to_id', $data['id'])
            ->orWhere('from_id',  $data['id'])
            ->where('to_id', Auth::user()->id)
            ->latest()
            ->paginate(20);

        if (count($messages) < 1) return new OperationResult(OperationResultEnum::SUCCESS->value, "
            <div class='d-flex justify-content-center align-items-center h-100 no_messages'>
                <p>Say hi and start messaging!</p>
            </div>
        ");

        return $messages;
    }
}
