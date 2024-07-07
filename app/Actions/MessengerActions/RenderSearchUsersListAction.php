<?php

namespace App\Actions\MessengerActions;

use Illuminate\Pagination\LengthAwarePaginator;

class RenderSearchUsersListAction
{
    public function __invoke(LengthAwarePaginator $records)
    {
        $list = null;

        foreach ($records as $record) {
            $list .= view('messenger.components.search-item', ['record' => $record])->render();
        }

        return $list;
    }
}
