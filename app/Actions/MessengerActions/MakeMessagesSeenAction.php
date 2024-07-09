<?php

namespace App\Actions\MessengerActions;

use App\Enums\ChatSettingsEnum;
use App\Enums\OperationResultEnum;
use App\Helpers\OperationResult;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MakeMessagesSeenAction
{
    public function __invoke(array $data)
    {
        $result = Message::query()
            ->where('from_id', $data['id'])
            ->where('to_id', Auth::user()->id)
            ->where('seen', ChatSettingsEnum::NOTSEEN->value)
            ->update([
                'seen' => ChatSettingsEnum::SEEN->value
            ]);

        if (!$result) return new OperationResult(OperationResultEnum::FAILURE->value, 'Failed to update the messages!');

        return $result;
    }
}
