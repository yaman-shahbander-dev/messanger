<?php

namespace App\Actions\MessengerActions;

use App\Enums\OperationResultEnum;
use App\Helpers\OperationResult;

class AddOrRemoveFavoriteAction
{
    public function __invoke(array $data)
    {
        $query = app(SetFavoredRecordConditionsAction::class)($data);

        $checkExistence = app(CheckTheExistenceOfFavoredRecord::class)($query);

        if (!$checkExistence) {
            $favorite = app(CreateFavoredRecordAction::class)($data);
            if ($favorite instanceof OperationResult) return $favorite;
            return new OperationResult(OperationResultEnum::SUCCESS->value, 'added');
        }

        $result = app(DeleteFavoredRecordAction::class)($query);
        if ($result instanceof OperationResult) return $result;

        return new OperationResult(OperationResultEnum::SUCCESS->value, 'removed');
    }
}
