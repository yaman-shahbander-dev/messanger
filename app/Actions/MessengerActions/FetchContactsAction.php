<?php

namespace App\Actions\MessengerActions;

use App\Enums\OperationResultEnum;
use App\Helpers\OperationResult;
use Illuminate\Pagination\LengthAwarePaginator;

class FetchContactsAction
{
    public function __invoke()
    {
        $users = app(GetContactsAction::class)();

        if (count($users) > 0) {
            return [
                'contacts' => $this->getLastMessages($users),
                'last_page' => $users->lastPage()
            ];
        }

        return new OperationResult(OperationResultEnum::SUCCESS->value, "
            <p>Your contacts list is empty!</p>
        ");
    }

    private function getLastMessages(LengthAwarePaginator $users): ?string
    {
        $contacts = null;

        foreach ($users as $user) {
            $contacts .= app(GetContactItemAction::class)($user);
        }

        return $contacts;
    }
}
