<?php

namespace App\Actions\MessengerActions;

use Illuminate\Database\Eloquent\Builder;

class CheckTheExistenceOfFavoredRecord
{
    public function __invoke(Builder $query): bool
    {
        return $query->exists();
    }
}
