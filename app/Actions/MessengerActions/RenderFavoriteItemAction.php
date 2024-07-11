<?php

namespace App\Actions\MessengerActions;

class RenderFavoriteItemAction
{
    public function __invoke($record)
    {
        return view('messenger.components.favorite-item', ['item' => $record]);
    }
}
