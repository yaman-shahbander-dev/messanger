<?php

namespace App\Actions\MessengerActions;

use App\Models\Favorite;
use App\Models\User;
use App\Helpers\OperationResult;
use App\Enums\OperationResultEnum;
use Illuminate\Support\Facades\Auth;

class FetchUserInfoByIdAction
{
    public function __invoke(int $id)
    {
        $user = User::query()
            ->where('id', $id)
            ->first();

        if (!$user) return new OperationResult(OperationResultEnum::FAILURE->value, 'Failed to find the user!');

        $user->favorite = $this->isFavorite($id);

        return $user;
    }

    private function isFavorite($id)
    {
        return Favorite::query()
            ->where('user_id', Auth::user()->id)
            ->where('favorite_id', $id)
            ->exists();
    }
}
