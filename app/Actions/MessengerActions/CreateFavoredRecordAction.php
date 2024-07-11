<?php

namespace App\Actions\MessengerActions;

use App\Enums\OperationResultEnum;
use App\Helpers\OperationResult;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CreateFavoredRecordAction
{
    public function __invoke(array $data): Model|OperationResult|Builder
    {
        $favorite = Favorite::query()
            ->create([
                'user_id' => Auth::user()->id,
                'favorite_id' => $data['id']
            ]);

        if (!$favorite) return new OperationResult(OperationResultEnum::FAILURE->value, 'Failed to create the favorite!');

        return $favorite;
    }
}
