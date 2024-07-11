<?php

namespace App\Actions\MessengerActions;

use App\Enums\OperationResultEnum;
use App\Helpers\OperationResult;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class DeleteMessageAction
{
    public function __invoke(array $data)
    {
        $message = $this->get($data['messageId']);

        if (!$message) return new OperationResult(OperationResultEnum::FAILURE->value, 'Failed to find the message!');

        if ($message->from_id === Auth::user()->id) {
            $result = $this->delete($message);

            if (!$result) return new OperationResult(OperationResultEnum::FAILURE->value, 'Failed to delete the message!');
            return $result;
        }

        return new OperationResult(OperationResultEnum::FAILURE->value, 'Failed to delete the message, the message belongs to the other user!');
    }

    private function get(int $id)
    {
        return Message::query()
            ->where('id', $id)
            ->first();
    }

    private function delete($message)
    {
        return $message->delete();
    }
}
