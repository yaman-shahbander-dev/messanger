<?php

namespace App\Actions\MessengerActions;

use App\Enums\OperationResultEnum;
use App\Helpers\OperationResult;
use App\Models\User;

class GetUserByIdAction
{
    public function __invoke(int $id)
    {
        $user = User::query()
            ->where('id', $id)
            ->first();

        if (!$user) return new OperationResult(OperationResultEnum::FAILURE->value, 'Failed to find the user!');

        return $user;
    }
}
