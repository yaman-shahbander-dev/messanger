<?php

namespace App\Actions\MessengerActions;

use App\Enums\OperationResultEnum;
use App\Helpers\OperationResult;
use Illuminate\Database\Eloquent\Builder;
class DeleteFavoredRecordAction
{
    public function __invoke(Builder $query): OperationResult|bool
    {
        $result = $query->delete();

        if (!$result) return new OperationResult(OperationResultEnum::FAILURE->value, 'Failed to delete the favorite!');

        return $result;
    }
}
