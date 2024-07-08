<?php

namespace App\Actions\MessengerActions;

use App\Models\User;
use App\Helpers\OperationResult;
use App\Enums\OperationResultEnum;

class FetchUserInfoByIdAction
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
